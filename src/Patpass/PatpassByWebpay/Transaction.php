<?php

namespace Transbank\Patpass\PatpassByWebpay;

use Transbank\Patpass\Options;
use Transbank\Patpass\PatpassByWebpay;
use Transbank\Patpass\PatpassByWebpay\Exceptions\TransactionCommitException;
use Transbank\Patpass\PatpassByWebpay\Exceptions\TransactionCreateException;
use Transbank\Patpass\PatpassByWebpay\Exceptions\TransactionStatusException;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionCommitResponse;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionCreateResponse;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionStatusResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;

class Transaction
{
    use InteractsWithWebpayApi;

    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions';

    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions';

    const GET_TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';

    public function create($buyOrder, $sessionId, $amount, $returnUrl, $details)
    {
        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'amount'     => $amount,
            'return_url' => $returnUrl,
            'wpm_detail' => $details,
        ];
        $endpoint = static::CREATE_TRANSACTION_ENDPOINT;

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }

        return new TransactionCreateResponse($response);
    }

    public function commit($token)
    {
        $payload = [];
        $endpoint = static::COMMIT_TRANSACTION_ENDPOINT;

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionCommitException::raise($exception);
        }

        return new TransactionCommitResponse($response);
    }

    public function status($token)
    {
        $payload = [];
        $endpoint = str_replace('{token}', $token, self::GET_TRANSACTION_STATUS_ENDPOINT);

        try {
            $response = $this->sendRequest('GET', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionStatusException::raise($exception);
        }

        return new TransactionStatusResponse($response);
    }

    /**
     * Get the default options if none are given.
     *
     * @return Options|null
     */
    public static function getGlobalOptions()
    {
        return PatpassByWebpay::getOptions();
    }

    public static function getDefaultOptions()
    {
        return PatpassByWebpay::getDefaultOptions();
    }
}
