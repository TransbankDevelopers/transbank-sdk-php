<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionStatusResponse;
use GuzzleHttp\Exception\GuzzleException;

class MallTransaction
{
    use InteractsWithWebpayApi;

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';
    const SEARCH_STRING = '{token}';

    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param string $returnUrl
     * @param array $details
     *
     * @throws MallTransactionCreateException
     * @throws GuzzleException
     *
     * @return MallTransactionCreateResponse
     */
    public function create(string $buyOrder, string $sessionId, string $returnUrl, array $details)
    {
        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'details'    => $details,
            'return_url' => $returnUrl,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                static::ENDPOINT_CREATE,
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCreateException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCreateResponse($response);
    }

    /**
     * @param string $token
     *
     * @throws MallTransactionCommitException
     * @throws GuzzleException
     *
     * @return MallTransactionCommitResponse
     */
    public function commit(string $token)
    {
        if (!is_string($token)) {
            throw new \InvalidArgumentException('Token parameter given is not string.');
        }
        if (!isset($token) || trim($token) === '') {
            throw new \InvalidArgumentException('Token parameter given is empty.');
        }

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_COMMIT),
                []
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCommitException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCommitResponse($response);
    }

    /**
     * @param string $token
     * @param string $buyOrder
     * @param string $childCommerceCode
     * @param int|float $amount
     *
     * @throws MallTransactionRefundException
     * @throws GuzzleException
     *
     * @return MallTransactionRefundResponse
     */
    public function refund(string $token, string $buyOrder, string $childCommerceCode, int|float $amount)
    {
        $payload = [
            'buy_order'     => $buyOrder,
            'commerce_code' => $childCommerceCode,
            'amount'        => $amount,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_REFUND),
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionRefundException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionRefundResponse($response);
    }

    /**
     * @param string $token
     *
     * @throws MallTransactionStatusException
     * @throws GuzzleException
     *
     * @return MallTransactionStatusResponse
     */
    public function status(string $token)
    {
        try {
            $response = $this->sendRequest(
                'GET',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_STATUS),
                []
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionStatusException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionStatusResponse($response);
    }

    /**
     * @param string $childCommerceCode
     * @param string $token
     * @param string $buyOrder
     * @param string $authorizationCode
     * @param int|float $captureAmount
     *
     * @throws MallTransactionCaptureException
     * @throws GuzzleException
     *
     * @return MallTransactionCaptureResponse
     */
    public function capture(
        string $childCommerceCode,
        string $token,
        string $buyOrder,
        string $authorizationCode,
        int|float $captureAmount
    ) {
        $payload = [
            'commerce_code'      => $childCommerceCode,
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $captureAmount,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_CAPTURE),
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCaptureException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCaptureResponse($response);
    }
}
