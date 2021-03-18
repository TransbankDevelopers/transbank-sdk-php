<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionStatusException;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionRefundResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionStatusResponse;

/**
 * Class Transaction.
 */
class Transaction
{
    use InteractsWithWebpayApi;

    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const REFUND_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const CAPTURE_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';

    /**
     * @param string       $buyOrder
     * @param string       $sessionId
     * @param int          $amount
     * @param string       $returnUrl
     * @param Options|null $options
     *
     * @throws TransactionCreateException
     *
     * @return TransactionCreateResponse
     **
     */
    public static function create($buyOrder, $sessionId, $amount, $returnUrl, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);

        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'amount'     => $amount,
            'return_url' => $returnUrl,
        ];

        try {
            $response = static::request(
                'POST',
                static::CREATE_TRANSACTION_ENDPOINT,
                $payload,
                $options
            );
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }

        return new TransactionCreateResponse($response);
    }

    /**
     * @param $token
     * @param null $options
     *
     * @throws TransactionCommitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionCommitResponse
     */
    public static function commit($token, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);

        try {
            $response = static::request(
                'PUT',
                str_replace('{token}', $token, static::COMMIT_TRANSACTION_ENDPOINT),
                null,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionCommitException::raise($e);
        }

        return new TransactionCommitResponse($response);
    }

    /**
     * @param $token
     * @param $amount
     * @param null $options
     *
     * @throws TransactionRefundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionRefundResponse
     */
    public static function refund($token, $amount, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);

        try {
            $response = static::request(
                'POST',
                str_replace('{token}', $token, static::REFUND_TRANSACTION_ENDPOINT),
                ['amount' => $amount],
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionRefundException::raise($e);
        }

        return new TransactionRefundResponse($response);
    }

    /**
     * @param $token
     * @param null $options
     *
     * @throws TransactionStatusException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionStatusResponse
     */
    public static function status($token, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);

        try {
            $response = static::request(
                'GET',
                str_replace('{token}', $token, static::TRANSACTION_STATUS_ENDPOINT),
                null,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionStatusException::raise($e);
        }

        return new TransactionStatusResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $captureAmount
     * @param null $options
     *
     * @throws TransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionCaptureResponse
     */
    public static function capture($token, $buyOrder, $authorizationCode, $captureAmount, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);

        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $captureAmount,
        ];

        try {
            $response = static::request(
                'PUT',
                str_replace('{token}', $token, static::CAPTURE_ENDPOINT),
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionCaptureException::raise($e);
        }

        return new TransactionCaptureResponse($response);
    }

    /**
     * @param $integrationEnvironment
     *
     * @return mixed|string
     */
    public static function getBaseUrl($integrationEnvironment)
    {
        return WebpayPlus::getIntegrationTypeUrl($integrationEnvironment);
    }
}
