<?php

namespace Transbank\TransaccionCompleta;

use Transbank\Common\Responses\DeferredCaptureHistoryResponse;
use Transbank\Common\Responses\IncreaseAmountResponse;
use Transbank\Common\Responses\IncreaseAuthorizationDateResponse;
use Transbank\Common\Responses\ReversePreAuthorizedAmountResponse;
use Transbank\TransaccionCompleta\Exceptions\TransactionCaptureException;
use Transbank\TransaccionCompleta\Exceptions\TransactionCommitException;
use Transbank\TransaccionCompleta\Exceptions\TransactionCreateException;
use Transbank\TransaccionCompleta\Exceptions\TransactionInstallmentsException;
use Transbank\TransaccionCompleta\Exceptions\TransactionRefundException;
use Transbank\TransaccionCompleta\Exceptions\TransactionStatusException;
use Transbank\TransaccionCompleta\Responses\TransactionCommitResponse;
use Transbank\TransaccionCompleta\Responses\TransactionCreateResponse;
use Transbank\TransaccionCompleta\Responses\TransactionInstallmentsResponse;
use Transbank\TransaccionCompleta\Responses\TransactionRefundResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Exceptions\DeferredCaptureHistoryException;
use Transbank\Webpay\Exceptions\IncreaseAmountException;
use Transbank\Webpay\Exceptions\IncreaseAuthorizationDateException;
use Transbank\Webpay\Exceptions\ReversePreAuthorizedAmountException;
use Transbank\Webpay\Options;

/**
 * Class Transaction.
 */
class Transaction
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

    /**
     * @param $buyOrder
     * @param $sessionId
     * @param $amount
     * @param $cvv
     * @param $cardNumber
     * @param $cardExpirationDate
     *
     * @throws TransactionCreateException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionCreateResponse
     */
    public function create(
        $buyOrder,
        $sessionId,
        $amount,
        $cvv,
        $cardNumber,
        $cardExpirationDate
    ) {
        $payload = [
            'buy_order'            => $buyOrder,
            'session_id'           => $sessionId,
            'amount'               => $amount,
            'cvv'                  => $cvv,
            'card_number'          => $cardNumber,
            'card_expiration_date' => $cardExpirationDate,
        ];

        try {
            $response = $this->sendRequest('POST', static::ENDPOINT_CREATE, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }

        return new TransactionCreateResponse($response);
    }

    /**
     * @param $token
     * @param $installmentsNumber
     *
     * @throws TransactionInstallmentsException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionInstallmentsResponse
     */
    public function installments(
        $token,
        $installmentsNumber
    ) {
        $payload = [
            'installments_number' => $installmentsNumber,
        ];

        $endpoint = str_replace('{token}', $token, static::ENDPOINT_INSTALLMENTS);

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionInstallmentsException::raise($exception);
        }

        return new TransactionInstallmentsResponse($response);
    }

    /**
     * @param $token
     * @param $idQueryInstallments
     * @param $deferredPeriodIndex
     * @param $gracePeriod
     *
     * @throws TransactionCommitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionCommitResponse
     */
    public function commit(
        $token,
        $idQueryInstallments = null,
        $deferredPeriodIndex = null,
        $gracePeriod = null
    ) {
        $payload = [
            'id_query_installments' => $idQueryInstallments,
            'deferred_period_index' => $deferredPeriodIndex,
            'grace_period'          => $gracePeriod,
        ];

        $endpoint = str_replace('{token}', $token, static::ENDPOINT_COMMIT);

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionCommitException::raise($exception);
        }

        return new TransactionCommitResponse($response);
    }

    /**
     * @param $token
     * @param $amount
     *
     * @throws TransactionRefundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionRefundResponse
     */
    public function refund($token, $amount)
    {
        $payload = [
            'amount' => $amount,
        ];

        $endpoint = str_replace('{token}', $token, static::ENDPOINT_REFUND);

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionRefundException::raise($exception);
        }

        return new TransactionRefundResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionStatusException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return Responses\TransactionStatusResponse
     */
    public function status($token)
    {
        $endpoint = str_replace('{token}', $token, static::ENDPOINT_STATUS);

        try {
            $response = $this->sendRequest('GET', $endpoint, null);
        } catch (WebpayRequestException $exception) {
            throw TransactionStatusException::raise($exception);
        }

        return new \Transbank\TransaccionCompleta\Responses\TransactionStatusResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $captureAmount
     *
     * @throws TransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return Responses\TransactionCaptureResponse
     */
    public function capture($token, $buyOrder, $authorizationCode, $captureAmount)
    {
        $endpoint = str_replace('{token}', $token, static::ENDPOINT_CAPTURE);

        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => (int) $captureAmount,
        ];

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionCaptureException::raise($exception);
        }

        return new Responses\TransactionCaptureResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $amount
     * @param $commerceCode
     *
     * @throws IncreaseAmountException
     * @throws GuzzleException
     *
     * @return IncreaseAmountResponse
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
            throw IncreaseAmountException::raise($e);
        }

        return new IncreaseAmountResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $commerceCode
     *
     * @throws IncreaseAuthorizationDateException
     * @throws GuzzleException
     *
     * @return IncreaseAuthorizationDateResponse
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
            throw IncreaseAuthorizationDateException::raise($e);
        }

        return new IncreaseAuthorizationDateResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param $amount
     * @param $commerceCode
     *
     * @throws ReversePreAuthorizedAmountException
     * @throws GuzzleException
     *
     * @return ReversePreAuthorizedAmountResponse
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
            throw ReversePreAuthorizedAmountException::raise($e);
        }

        return new ReversePreAuthorizedAmountResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $commerceCode
     *
     * @throws DeferredCaptureHistoryException
     * @throws GuzzleException
     *
     * @return DeferredCaptureHistoryResponse
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
            throw DeferredCaptureHistoryException::raise($e);
        }

        return new DeferredCaptureHistoryResponse($response);
    }


    /**
     * Get the default options if none are given.
     *
     * @return Options
     */
    public static function getDefaultOptions()
    {
        return Options::forIntegration(TransaccionCompleta::DEFAULT_COMMERCE_CODE);
    }

    /**
     * @return Options|null
     */
    public static function getGlobalOptions()
    {
        return TransaccionCompleta::getOptions();
    }
}
