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

class MallTransaction
{
    use InteractsWithWebpayApi;

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';

    /**
     * @param $buyOrder
     * @param $sessionId
     * @param $returnUrl
     * @param $details
     *
     * @throws MallTransactionCreateException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionCreateResponse
     */
    public function create($buyOrder, $sessionId, $returnUrl, $details)
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
            throw new MallTransactionCreateException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCreateResponse($response);
    }

    /**
     * @param $token
     *
     * @throws MallTransactionCommitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionCommitResponse
     */
    public function commit($token)
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
                str_replace('{token}', $token, static::ENDPOINT_COMMIT),
                null
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCommitException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCommitResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $childCommerceCode
     * @param $amount
     *
     * @throws MallTransactionRefundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionRefundResponse
     */
    public function refund($token, $buyOrder, $childCommerceCode, $amount)
    {
        $payload = [
            'buy_order'     => $buyOrder,
            'commerce_code' => $childCommerceCode,
            'amount'        => $amount,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                str_replace('{token}', $token, static::ENDPOINT_REFUND),
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionRefundException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionRefundResponse($response);
    }

    /**
     * @param $token
     *
     * @throws MallTransactionStatusException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionStatusResponse
     */
    public function status($token)
    {
        try {
            $response = $this->sendRequest(
                'GET',
                str_replace('{token}', $token, static::ENDPOINT_STATUS),
                null
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionStatusException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionStatusResponse($response);
    }

    /**
     * @param $childCommerceCode
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param null $captureAmount
     *
     * @throws MallTransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionCaptureResponse
     */
    public function capture(
        $childCommerceCode,
        $token,
        $buyOrder,
        $authorizationCode,
        $captureAmount
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
                str_replace('{token}', $token, static::ENDPOINT_CAPTURE),
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCaptureException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCaptureResponse($response);
    }
}
