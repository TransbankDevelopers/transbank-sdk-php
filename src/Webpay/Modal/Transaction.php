<?php

namespace Transbank\Webpay\Modal;

use Transbank\Webpay\Exceptions\WebpayRequestException;
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     **
     */
    public static function create($amount, $buyOrder, $sessionId = null, Options $options = null)
    {
        $options = WebpayModal::getDefaultOptions($options);
        
        if ($sessionId === null) {
            $sessionId = uniqid();
        }

        $payload = [
            'buy_order' => $buyOrder,
            'session_id' => $sessionId,
            'amount' => $amount
        ];

        try {
            $response = static::request('POST', static::CREATE_TRANSACTION_ENDPOINT, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }
        
        return new TransactionCreateResponse($response);
    }

    /**
     * @param string $token
     * @param Options|null $options
     *
     * @return TransactionCommitResponse
     * @throws TransactionCommitException|\GuzzleHttp\Exception\GuzzleException
     **
     */
    public static function commit($token, Options $options = null)
    {
        $options = WebpayModal::getDefaultOptions($options);

        $endpoint = str_replace('{token}', $token, static::COMMIT_TRANSACTION_ENDPOINT);
        try {
            $response = static::request('PUT', $endpoint, [], $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionCommitException::raise($exception);
        }
        
        return new TransactionCommitResponse($response);
    }
    
    /**
     * @param $token
     * @param Options|null $options
     * @return TransactionStatusResponse
     * @throws TransactionStatusException
     * @throws \GuzzleHttp\Exception\GuzzleException|TransactionStatusException
     */
    public static function status($token, Options $options = null)
    {
        if ($options == null) {
            $options = WebpayModal::getDefaultOptions();
        }

        $endpoint = str_replace('{token}', $token, static::STATUS_TRANSACTION_ENDPOINT);
        try {
            $response = static::request('GET', $endpoint, [], $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionStatusException::raise($exception);
        }
        return new TransactionStatusResponse($response);
    }
    
    /**
     * @param $token
     * @param $amount
     * @param Options|null $options
     * @return TransactionRefundResponse
     * @throws TransactionRefundException
     * @throws \GuzzleHttp\Exception\GuzzleException|TransactionStatusException
     */
    public static function refund($token, $amount, Options $options = null)
    {
        $options = WebpayModal::getDefaultOptions($options);

        $endpoint = str_replace('{token}', $token, static::REFUND_TRANSACTION_ENDPOINT);
        try {
            $response = static::request(
                'POST',
                $endpoint,
                ['amount' => $amount],
                $options
            );
        } catch (WebpayRequestException $exception) {
            throw TransactionStatusException::raise($exception);
        }
        

        return new TransactionRefundResponse($response);
    }
}
