<?php

/**
 * Class MallTransaction.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta;

use Transbank\TransaccionCompleta\Exceptions\MallTransactionCommitException;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionCreateException;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionRefundException;
use Transbank\TransaccionCompleta\Exceptions\MallTransactionStatusException;
use Transbank\TransaccionCompleta\Exceptions\TransactionInstallmentsException;
use Transbank\TransaccionCompleta\Responses\MallTransactionCommitResponse;
use Transbank\TransaccionCompleta\Responses\MallTransactionCreateResponse;
use Transbank\TransaccionCompleta\Responses\MallTransactionInstallmentsResponse;
use Transbank\TransaccionCompleta\Responses\MallTransactionRefundResponse;
use Transbank\TransaccionCompleta\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Utils\InteractsWithWebpayApi;

class MallTransaction
{
    use InteractsWithWebpayApi;

    const CREATE_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions';
    const INSTALLMENTS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}/installments';
    const COMMIT_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const REFUND_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const STATUS_TRANSACTION_ENDPOINT = '/rswebpaytransaction/api/webpay/v1.2/transactions/{token}';

    public static function create(
        $buyOrder,
        $sessionId,
        $cardNumber,
        $cardExpirationDate,
        $details,
        $options = null
    ) {
        $options = MallTransaccionCompleta::getDefaultOptions($options);

        $payload = [
            'buy_order'            => $buyOrder,
            'session_id'           => $sessionId,
            'card_number'          => $cardNumber,
            'card_expiration_date' => $cardExpirationDate,
            'details'              => $details,
        ];

        try {
            $response = static::request('POST', static::CREATE_TRANSACTION_ENDPOINT, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionCreateException::raise($exception);
        }

        return new MallTransactionCreateResponse($response);
    }

    public static function installments(
        $token,
        $details,
        $options = null
    ) {
        $options = MallTransaccionCompleta::getDefaultOptions($options);

        $endpoint = str_replace('{token}', $token, self::INSTALLMENTS_TRANSACTION_ENDPOINT);

        try {
            return array_map(function ($detail) use ($endpoint, $options) {
                $payload = [
                    'commerce_code'       => $detail['commerce_code'],
                    'buy_order'           => $detail['buy_order'],
                    'installments_number' => $detail['installments_number'],
                ];
                $response = static::request('POST', $endpoint, $payload, $options);

                return new MallTransactionInstallmentsResponse($response);
            }, $details);
        } catch (WebpayRequestException $exception) {
            throw TransactionInstallmentsException::raise($exception);
        }
    }

    public static function commit(
        $token,
        $details,
        $options = null
    ) {
        $options = MallTransaccionCompleta::getDefaultOptions($options);

        $payload = [
            'details' => $details,
        ];

        $endpoint = str_replace('{token}', $token, static::COMMIT_TRANSACTION_ENDPOINT);

        try {
            $response = static::request('PUT', $endpoint, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionCommitException::raise($exception);
        }

        return new MallTransactionCommitResponse($response);
    }

    public static function refund(
        $token,
        $buyOrder,
        $commerceCodeChild,
        $amount,
        $options = null
    ) {
        $options = MallTransaccionCompleta::getDefaultOptions($options);

        $payload = [
            'buy_order'     => $buyOrder,
            'commerce_code' => $commerceCodeChild,
            'amount'        => $amount,
        ];

        $endpoint = str_replace('{token}', $token, static::REFUND_TRANSACTION_ENDPOINT);

        try {
            $response = static::request('POST', $endpoint, $payload, $options);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionRefundException::raise($exception);
        }

        return new MallTransactionRefundResponse($response);
    }

    public static function status(
        $token,
        $options = null
    ) {
        $options = MallTransaccionCompleta::getDefaultOptions($options);

        $endpoint = str_replace('{token}', $token, static::STATUS_TRANSACTION_ENDPOINT);

        try {
            $response = static::request('GET', $endpoint, null, $options);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionStatusException::raise($exception);
        }

        return new MallTransactionStatusResponse($response);
    }

    /**
     * @param $integrationEnvironment
     *
     * @return mixed|string
     */
    public static function getBaseUrl($integrationEnvironment)
    {
        return MallTransaccionCompleta::getIntegrationTypeUrl($integrationEnvironment);
    }
}
