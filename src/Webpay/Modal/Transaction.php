<?php

namespace Transbank\Webpay\Modal;

use GuzzleHttp\Exception\GuzzleException;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
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

    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const STATUS_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const REFUND_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';

    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param int    $amount
     *
     * @throws TransactionCreateException
     * @throws GuzzleException|TransactionCreateException
     *
     * @return TransactionCreateResponse
     **
     */
    public function create($amount, $buyOrder, $sessionId = null)
    {
        if ($sessionId === null) {
            $sessionId = uniqid();
        }

        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'amount'     => $amount,
        ];

        try {
            $response = $this->sendRequest('POST', static::CREATE_TRANSACTION_ENDPOINT, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }

        return new TransactionCreateResponse($response);
    }

    /**
     * @param string $token
     *
     * @throws TransactionCommitException|GuzzleException
     *
     * @return TransactionCommitResponse
     **
     */
    public function commit($token)
    {
        $endpoint = str_replace('{token}', $token, static::COMMIT_TRANSACTION_ENDPOINT);

        try {
            $response = $this->sendRequest('PUT', $endpoint, []);
        } catch (WebpayRequestException $exception) {
            throw TransactionCommitException::raise($exception);
        }

        return new TransactionCommitResponse($response);
    }

    /**
     * @param $token
     *
     * @throws GuzzleException
     * @throws TransactionStatusException
     *
     * @return TransactionStatusResponse
     */
    public function status($token)
    {
        $endpoint = str_replace('{token}', $token, static::STATUS_TRANSACTION_ENDPOINT);

        try {
            $response = $this->sendRequest('GET', $endpoint, []);
        } catch (WebpayRequestException $exception) {
            throw TransactionStatusException::raise($exception);
        }

        return new TransactionStatusResponse($response);
    }

    /**
     * @param $token
     * @param $amount
     * @param Options|null $options
     *
     * @throws GuzzleException|TransactionRefundException
     *
     * @return TransactionRefundResponse
     */
    public function refund($token, $amount)
    {
        $endpoint = str_replace('{token}', $token, static::REFUND_TRANSACTION_ENDPOINT);

        try {
            $response = $this->sendRequest(
                'POST',
                $endpoint,
                ['amount' => $amount]
            );
        } catch (WebpayRequestException $exception) {
            throw TransactionRefundException::raise($exception);
        }

        return new TransactionRefundResponse($response);
    }

    public static function getDefaultOptions()
    {
        return Options::forIntegration(WebpayModal::DEFAULT_COMMERCE_CODE, WebpayModal::DEFAULT_API_KEY);
    }

    public static function getGlobalOptions()
    {
        return WebpayModal::getOptions();
    }
}
