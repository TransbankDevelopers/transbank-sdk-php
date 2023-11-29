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
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionStatusException;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionRefundResponse;

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
     * @throws TransactionCreateException
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
            throw MallTransactionCreateException::raise($exception);
        }

        return new MallTransactionCreateResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionCommitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionCommitResponse
     */
    public function commit($token)
    {
        if (!is_string($token)) {
            throw new InvalidArgumentException('Token parameter given is not string.');
        }
        if (!isset($token) || trim($token) === '') {
            throw new InvalidArgumentException('Token parameter given is empty.');
        }

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_COMMIT),
                null
            );
        } catch (WebpayRequestException $e) {
            throw MallTransactionCommitException::raise($e);
        }

        return new MallTransactionCommitResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $childCommerceCode
     * @param $amount
     *
     * @throws TransactionRefundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionRefundResponse
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
        } catch (WebpayRequestException $e) {
            throw MallTransactionRefundException::raise($e);
        }

        return new MallTransactionRefundResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionStatusException
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
        } catch (WebpayRequestException $e) {
            throw MallTransactionStatusException::raise($e);
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
     * @throws TransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionCaptureResponse
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
        } catch (WebpayRequestException $e) {
            throw MallTransactionCaptureException::raise($e);
        }

        return new MallTransactionCaptureResponse($response);
    }

    /**
     * Get the default options if none are given.
     *
     * @return Options
     */
    public static function getDefaultOptions()
    {
        return Options::forIntegration(WebpayPlus::DEFAULT_MALL_COMMERCE_CODE);
    }

    /**
     * Get the default options if none are given.
     *
     * @return Options|null
     */
    public static function getGlobalOptions()
    {
        return WebpayPlus::getOptions();
    }
}
