<?php

/**
 * Class MallTransaction.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta;

use Transbank\TransaccionCompleta\Exceptions\MallTransactionCaptureException;
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
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

class MallTransaction
{
    use InteractsWithWebpayApi;

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const ENDPOINT_INSTALLMENTS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/installments';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';

    public function create(
        $buyOrder,
        $sessionId,
        $cardNumber,
        $cardExpirationDate,
        $details,
        $cvv = null
    ) {
        $payload = [
            'buy_order'            => $buyOrder,
            'session_id'           => $sessionId,
            'card_number'          => $cardNumber,
            'card_expiration_date' => $cardExpirationDate,
            'details'              => $details,
        ];
        if ($cvv) {
            $payload['cvv'] = $cvv;
        }

        try {
            $response = $this->sendRequest('POST', static::ENDPOINT_CREATE, $payload);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionCreateException::raise($exception);
        }

        return new MallTransactionCreateResponse($response);
    }

    public function installments(
        $token,
        $details
    ) {
        $endpoint = str_replace('{token}', $token, static::ENDPOINT_INSTALLMENTS);

        try {
            return array_map(function ($detail) use ($endpoint) {
                $payload = [
                    'commerce_code'       => $detail['commerce_code'],
                    'buy_order'           => $detail['buy_order'],
                    'installments_number' => $detail['installments_number'],
                ];
                $response = $this->sendRequest('POST', $endpoint, $payload);

                return new MallTransactionInstallmentsResponse($response);
            }, $details);
        } catch (WebpayRequestException $exception) {
            throw TransactionInstallmentsException::raise($exception);
        }
    }

    public function commit(
        $token,
        $details
    ) {
        $payload = [
            'details' => $details,
        ];

        $endpoint = str_replace('{token}', $token, static::ENDPOINT_COMMIT);

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionCommitException::raise($exception);
        }

        return new MallTransactionCommitResponse($response);
    }

    public function refund(
        $token,
        $buyOrder,
        $commerceCodeChild,
        $amount
    ) {
        $payload = [
            'buy_order'     => $buyOrder,
            'commerce_code' => $commerceCodeChild,
            'amount'        => $amount,
        ];

        $endpoint = str_replace('{token}', $token, static::ENDPOINT_REFUND);

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionRefundException::raise($exception);
        }

        return new MallTransactionRefundResponse($response);
    }

    public function status($token)
    {
        $endpoint = str_replace('{token}', $token, static::ENDPOINT_STATUS);

        try {
            $response = $this->sendRequest('GET', $endpoint, null);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionStatusException::raise($exception);
        }

        return new MallTransactionStatusResponse($response);
    }

    /**
     * @param $token
     * @param $commerceCode
     * @param $buyOrder
     * @param $authorizationCode
     * @param $captureAmount
     *
     * @throws MallTransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return Responses\MallTransactionCaptureResponse
     */
    public function capture($token, $commerceCode, $buyOrder, $authorizationCode, $captureAmount)
    {
        $endpoint = str_replace('{token}', $token, static::ENDPOINT_CAPTURE);

        $payload = [
            'buy_order'          => $buyOrder,
            'commerce_code'      => $commerceCode,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => (int) $captureAmount,
        ];

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw MallTransactionCaptureException::raise($exception);
        }

        return new Responses\MallTransactionCaptureResponse($response);
    }

    public static function getDefaultOptions()
    {
        return Options::forIntegration(TransaccionCompleta::DEFAULT_MALL_COMMERCE_CODE);
    }

    public static function getGlobalOptions()
    {
        return TransaccionCompleta::getOptions();
    }
}
