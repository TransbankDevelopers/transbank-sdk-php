<?php

namespace Transbank\Webpay\WebpayPlus;

use GuzzleHttp\Exception\GuzzleException;
use Transbank\Webpay\WebpayPlus\Responses\DeferredCaptureHistoryResponse;
use Transbank\Webpay\WebpayPlus\Responses\IncreaseAmountResponse;
use Transbank\Webpay\WebpayPlus\Responses\IncreaseAuthorizationDateResponse;
use Transbank\Webpay\WebpayPlus\Responses\ReversePreAuthorizedAmountResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\DeferredCaptureHistoryException;
use Transbank\Webpay\Exceptions\IncreaseAmountException;
use Transbank\Webpay\Exceptions\IncreaseAuthorizationDateException;
use Transbank\Webpay\Exceptions\ReversePreAuthorizedAmountException;
use Transbank\Webpay\Exceptions\WebpayRequestException;
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

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.3/transactions';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/capture';
    const ENDPOINT_INCREASE_AMOUNT = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/amount';
    const ENDPOINT_INCREASE_AUTHORIZATION_DATE = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/authorization_date';
    const ENDPOINT_REVERSE_PRE_AUTHORIZE_AMOUNT = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/reverse/amount';
    const ENDPOINT_DEFERRED_CAPTURE_HISTORY = 'rswebpaytransaction/api/webpay/v1.3/transactions/{token}/details';

    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param int    $amount
     * @param string $returnUrl
     *
     * @throws TransactionCreateException
     * @throws GuzzleException
     *
     * @return TransactionCreateResponse
     */
    public function create($buyOrder, $sessionId, $amount, $returnUrl)
    {
        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'amount'     => $amount,
            'return_url' => $returnUrl,
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
     *
     * @throws TransactionCommitException
     * @throws GuzzleException
     *
     * @return TransactionCommitResponse
     */
    public function commit($token)
    {
        if (!is_string($token)) {
            throw new InvalidArgumentException('Token parameter given is not string.');
        }
        if (!isset($token) || trim($token) === '') {
            throw new InvalidArgumentException('Token parameter given is empty.');
        }

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_COMMIT),
                null
            );
        } catch (WebpayRequestException $e) {
            throw TransactionCommitException::raise($e);
        }

        return new TransactionCommitResponse($response);
    }

    /**
     * @param $token
     * @param $amount
     *
     * @throws TransactionRefundException
     * @throws GuzzleException
     *
     * @return TransactionRefundResponse
     */
    public function refund($token, $amount)
    {
        try {
            $response = $this->sendRequest(
                'POST',
                str_replace('{token}', $token, static::ENDPOINT_REFUND),
                ['amount' => $amount]
            );
        } catch (WebpayRequestException $e) {
            throw TransactionRefundException::raise($e);
        }

        return new TransactionRefundResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionStatusException
     * @throws GuzzleException
     *
     * @return TransactionStatusResponse
     */
    public function status($token)
    {
        try {
            $response = $this->sendRequest(
                'GET',
                str_replace('{token}', $token, static::ENDPOINT_STATUS),
                null
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
     *
     * @throws TransactionCaptureException
     * @throws GuzzleException
     *
     * @return TransactionCaptureResponse
     */
    public function capture($token, $buyOrder, $authorizationCode, $captureAmount)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $captureAmount,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_CAPTURE),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw TransactionCaptureException::raise($e);
        }

        return new TransactionCaptureResponse($response);
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
            'amount'             => $amount,
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
    public function deferredCaptureHistory($token)
    {
        if (!is_string($token)) {
            throw new InvalidArgumentException('Token parameter given is not string.');
        }
        if (!isset($token) || trim($token) === '') {
            throw new InvalidArgumentException('Token parameter given is empty.');
        }

        try {
            $response = $this->sendRequest(
                'GET',
                str_replace('{token}', $token, static::ENDPOINT_DEFERRED_CAPTURE_HISTORY),
                null
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
        return Options::forIntegration(WebpayPlus::DEFAULT_COMMERCE_CODE);
    }

    /**
     * Get the default options if none are given.
     *
     * @return Options|null
     */
    public static function getGlobalOptions()
    {
        return WebpayPlus::getOptions();
    }
}
