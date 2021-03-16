<?php

namespace Transbank\TransaccionCompleta;

use Transbank\TransaccionCompleta\Exceptions\TransactionCommitException;
use Transbank\TransaccionCompleta\Exceptions\TransactionCreateException;
use Transbank\TransaccionCompleta\Exceptions\TransactionInstallmentsException;
use Transbank\TransaccionCompleta\Exceptions\TransactionRefundException;
use Transbank\TransaccionCompleta\Exceptions\TransactionStatusException;
use Transbank\TransaccionCompleta\Responses\TransactionCommitResponse;
use Transbank\TransaccionCompleta\Responses\TransactionCreateResponse;
use Transbank\TransaccionCompleta\Responses\TransactionInstallmentsResponse;
use Transbank\TransaccionCompleta\Responses\TransactionRefundResponse;
use Transbank\TransaccionCompleta\Responses\TransactionStatusResponse;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\InteractsWithWebpayApi;

class Transaction
{
    use InteractsWithWebpayApi;

    const CREATE_TRANSACTION_ENDPOINT  = '/rswebpaytransaction/api/webpay/v1.2/transactions';
    const INSTALLMENTS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}/installments';
    const COMMIT_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const REFUND_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const STATUS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}';

    public static function create(
        $buyOrder,
        $sessionId,
        $amount,
        $cvv,
        $cardNumber,
        $cardExpirationDate,
        $options = null
    ) {
        $options = TransaccionCompleta::getDefaultOptions($options);

        $payload = [
            'buy_order'            => $buyOrder,
            'session_id'           => $sessionId,
            'amount'               => $amount,
            'cvv'                  => $cvv,
            'card_number'          => $cardNumber,
            'card_expiration_date' => $cardExpirationDate,
        ];

        try {
            $response = static::request('POST', static::CREATE_TRANSACTION_ENDPOINT, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }

        return new TransactionCreateResponse($response);
    }

    public static function installments(
        $token,
        $installmentsNumber,
        $options = null
    ) {
        $options = TransaccionCompleta::getDefaultOptions($options);

        $payload = [
            'installments_number' => $installmentsNumber,
        ];

        $endpoint = str_replace('{token}', $token, self::INSTALLMENTS_TRANSACTION_ENDPOINT);

        try {
            $response = static::request('POST', $endpoint, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionInstallmentsException::raise($exception);
        }

        return new TransactionInstallmentsResponse($response);
    }

    public static function commit(
        $token,
        $idQueryInstallments,
        $deferredPeriodIndex,
        $gracePeriod,
        $options = null
    ) {
        $options = TransaccionCompleta::getDefaultOptions($options);

        $payload = [
            'id_query_installments' => $idQueryInstallments,
            'deferred_period_index' => $deferredPeriodIndex,
            'grace_period'          => $gracePeriod,
        ];

        $endpoint = str_replace('{token}', $token, self::COMMIT_TRANSACTION_ENDPOINT);

        try {
            $response = static::request('PUT', $endpoint, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionCommitException::raise($exception);
        }

        return new TransactionCommitResponse($response);
    }

    public static function refund(
        $token,
        $amount,
        $options = null
    ) {
        $options = TransaccionCompleta::getDefaultOptions($options);

        $payload = [
            'amount' => $amount,
        ];

        $endpoint = str_replace('{token}', $token, self::REFUND_TRANSACTION_ENDPOINT);

        try {
            $response = static::request('POST', $endpoint, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionRefundException::raise($exception);
        }

        return new TransactionRefundResponse($response);
    }

    public static function status(
        $token,
        $options = null
    ) {
        $options = TransaccionCompleta::getDefaultOptions($options);

        $endpoint = str_replace('{token}', $token, self::STATUS_TRANSACTION_ENDPOINT);

        try {
            $response = static::request('GET', $endpoint, null, $options);
        } catch (WebpayRequestException $exception) {
            throw TransactionStatusException::raise($exception);
        }

        return new TransactionStatusResponse($response);
    }

    /**
     * @param $integrationEnvironment
     *
     * @return mixed|string
     */
    public static function getBaseUrl($integrationEnvironment)
    {
        return TransaccionCompleta::getIntegrationTypeUrl($integrationEnvironment);
    }
}
