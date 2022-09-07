<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Webpay\WebpayPlus\Responses\MallDeferredCaptureHistoryResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallIncreaseAmountResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallIncreaseAuthorizationDateResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallReversePreAuthorizedAmountResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\MallDeferredCaptureHistoryException;
use Transbank\Webpay\Exceptions\MallIncreaseAmountException;
use Transbank\Webpay\Exceptions\MallIncreaseAuthorizationDateException;
use Transbank\Webpay\Exceptions\MallReversePreAuthorizedAmountException;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionStatusException;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionRefundResponse;

class MallTransaction
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
     * @param $buyOrder
     * @param $sessionId
     * @param $returnUrl
     * @param $details
     *
     * @throws TransactionCreateException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionCreateResponse
     */
    public function create($buyOrder, $sessionId, $returnUrl, $details)
    {
        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'details'    => $details,
            'return_url' => $returnUrl,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                static::ENDPOINT_CREATE,
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw MallTransactionCreateException::raise($exception);
        }

        return new MallTransactionCreateResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionCommitException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionCommitResponse
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
            throw MallTransactionCommitException::raise($e);
        }

        return new MallTransactionCommitResponse($response);
    }

    /**
     * @param $token
     * @param $buyOrder
     * @param $childCommerceCode
     * @param $amount
     *
     * @throws TransactionRefundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionRefundResponse
     */
    public function refund($token, $buyOrder, $childCommerceCode, $amount)
    {
        $payload = [
            'buy_order'     => $buyOrder,
            'commerce_code' => $childCommerceCode,
            'amount'        => $amount,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                str_replace('{token}', $token, static::ENDPOINT_REFUND),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallTransactionRefundException::raise($e);
        }

        return new MallTransactionRefundResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionStatusException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionStatusResponse
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
            throw MallTransactionStatusException::raise($e);
        }

        return new MallTransactionStatusResponse($response);
    }

    /**
     * @param $childCommerceCode
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param null $captureAmount
     *
     * @throws TransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionCaptureResponse
     */
    public function capture(
        $childCommerceCode,
        $token,
        $buyOrder,
        $authorizationCode,
        $captureAmount
    ) {
        $payload = [
            'commerce_code'      => $childCommerceCode,
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
            throw MallTransactionCaptureException::raise($e);
        }

        return new MallTransactionCaptureResponse($response);
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
    public function increaseAmount($token, $buyOrder, $authorizationCode, $amount, $childCommerceCode)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'amount'             => $amount,
            'commerce_code'      => $childCommerceCode,
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
                'POST',
                str_replace('{token}', $token, static::ENDPOINT_DEFERRED_CAPTURE_HISTORY),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallDeferredCaptureHistoryException::raise($e);
        }

        return new MallDeferredCaptureHistoryResponse($response);
    }

    /**
     * Get the default options if none are given.
     *
     * @return Options
     */
    public static function getDefaultOptions()
    {
        return Options::forIntegration(WebpayPlus::DEFAULT_MALL_COMMERCE_CODE);
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
