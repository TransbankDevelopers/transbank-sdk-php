<?php

namespace Transbank\Webpay\Modal;

use Transbank\Webpay\InteractsWithWebpayApi;
use Transbank\Webpay\Modal\Exceptions\TransactionCommitException;
use Transbank\Webpay\Modal\Exceptions\TransactionCreateException;
use Transbank\Webpay\Modal\Exceptions\TransactionRefundException;
use Transbank\Webpay\Modal\Exceptions\TransactionStatusException;
use Transbank\Webpay\Modal\Responses\TransactionCommitResponse;
use Transbank\Webpay\Modal\Responses\TransactionCreateResponse;
use Transbank\Webpay\Modal\Responses\TransactionRefundResponse;
use Transbank\Webpay\Modal\Responses\TransactionStatusResponse;
use Transbank\Webpay\Options;

class Transaction
{
    use InteractsWithWebpayApi;

    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions';
    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/{token}';
    const STATUS_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/{token}';
    const REFUND_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/{token}/refunds';

    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param integer $amount
     * @param Options|null $options
     *
     * @return TransactionCreateResponse
     * @throws TransactionCreateException
     **
     */
    public static function create($amount, $buyOrder, $sessionId = null, Options $options = null)
    {
        if ($options == null) {
            $options = WebpayModal::getDefaultOptions();
        }
        if ($sessionId === null) {
            $sessionId = uniqid();
        }

        $payload = [
            "buy_order" => $buyOrder,
            "session_id" => $sessionId,
            "amount" => $amount
        ];

        $responseJson = static::request('POST', static::CREATE_TRANSACTION_ENDPOINT, $payload, $options, TransactionCreateException::class);

        return new TransactionCreateResponse($responseJson);

    }

    /**
     * @param string $token
     * @param Options|null $options
     *
     * @return TransactionCommitResponse
     * @throws TransactionCommitException
     **
     */
    public static function commit($token, Options $options = null)
    {
        if ($options == null) {
            $options = WebpayModal::getDefaultOptions();
        }

        $endpoint = str_replace('{token}', $token, static::COMMIT_TRANSACTION_ENDPOINT);
        $response = static::request('PUT', $endpoint, [], $options, TransactionCommitException::class);
        return new TransactionCommitResponse($response);

    }

    public static function status($token, Options $options = null)
    {
        if ($options == null) {
            $options = WebpayModal::getDefaultOptions();
        }

        $endpoint = str_replace('{token}', $token, static::STATUS_TRANSACTION_ENDPOINT);
        $response = static::request('GET', $endpoint, [], $options, TransactionStatusException::class);
        return new TransactionStatusResponse($response);

    }

    public static function refund($token, $amount, Options $options = null)
    {
        if ($options == null) {
            $options = WebpayModal::getDefaultOptions();
        }

        $endpoint = str_replace('{token}', $token, static::REFUND_TRANSACTION_ENDPOINT);
        $response = static::request(
            'POST',
            $endpoint,
            ['amount' => $amount],
            $options,
            TransactionRefundException::class);

        return new TransactionRefundResponse($response);

    }

}
