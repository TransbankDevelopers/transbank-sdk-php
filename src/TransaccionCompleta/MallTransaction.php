<?php

/**
 * Class MallTransaction.
 *
 * @category
 */

namespace Transbank\TransaccionCompleta;

use Transbank\Common\Responses\MallDeferredCaptureHistoryResponse;
use Transbank\Common\Responses\MallIncreaseAmountResponse;
use Transbank\Common\Responses\MallIncreaseAuthorizationDateResponse;
use Transbank\Common\Responses\MallReversePreAuthorizedAmountResponse;
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
use Transbank\Webpay\Exceptions\MallDeferredCaptureHistoryException;
use Transbank\Webpay\Exceptions\MallIncreaseAmountException;
use Transbank\Webpay\Exceptions\MallIncreaseAuthorizationDateException;
use Transbank\Webpay\Exceptions\MallReversePreAuthorizedAmountException;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

class MallTransaction
{
    use InteractsWithWebpayApi;

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.3/transactions';
    const ENDPOINT_INSTALLMENTS = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/installments';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/capture';
    const ENDPOINT_INCREASE_AMOUNT = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/amount';
    const ENDPOINT_INCREASE_AUTHORIZATION_DATE = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/authorization_date';
    const ENDPOINT_REVERSE_PRE_AUTHORIZE_AMOUNT = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/reverse/amount';
    const ENDPOINT_DEFERRED_CAPTURE_HISTORY = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/details';

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

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $amount
     * @param $commerceCode
     *
     * @throws MallIncreaseAmountException
     * @throws GuzzleException
     *
     * @return MallIncreaseAmountResponse
     */
    public function increaseAmount($token, $buyOrder, $authorizationCode, $amount, $commerceCode)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $amount,
            'commerce_code'      => $commerceCode,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_INCREASE_AMOUNT),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallIncreaseAmountException::raise($e);
        }

        return new MallIncreaseAmountResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $commerceCode
     *
     * @throws MallIncreaseAuthorizationDateException
     * @throws GuzzleException
     *
     * @return MallIncreaseAuthorizationDateResponse
     */
    public function increaseAuthorizationDate($token, $buyOrder, $authorizationCode, $commerceCode)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'commerce_code'      => $commerceCode,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_INCREASE_AUTHORIZATION_DATE),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallIncreaseAuthorizationDateException::raise($e);
        }

        return new MallIncreaseAuthorizationDateResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $amount
     * @param $commerceCode
     *
     * @throws MallReversePreAuthorizedAmountException
     * @throws GuzzleException
     *
     * @return MallReversePreAuthorizedAmountResponse
     */
    public function reversePreAuthorizedAmount($token, $buyOrder, $authorizationCode, $amount, $commerceCode)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'amount'             => $amount,
            'commerce_code'      => $commerceCode,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_REVERSE_PRE_AUTHORIZE_AMOUNT),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallReversePreAuthorizedAmountException::raise($e);
        }

        return new MallReversePreAuthorizedAmountResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $commerceCode
     *
     * @throws MallDeferredCaptureHistoryException
     * @throws GuzzleException
     *
     * @return MallDeferredCaptureHistoryResponse
     */
    public function deferredCaptureHistory($token, $buyOrder, $commerceCode)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'commerce_code'      => $commerceCode,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_DEFERRED_CAPTURE_HISTORY),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallDeferredCaptureHistoryException::raise($e);
        }

        return new MallDeferredCaptureHistoryResponse($response);
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
