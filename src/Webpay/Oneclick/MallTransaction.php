<?php

namespace Transbank\Webpay\Oneclick;

use Transbank\Webpay\Oneclick\Responses\MallDeferredCaptureHistoryResponse;
use Transbank\Webpay\Oneclick\Responses\MallIncreaseAmountResponse;
use Transbank\Webpay\Oneclick\Responses\MallIncreaseAuthorizationDateResponse;
use Transbank\Webpay\Oneclick\Responses\MallReversePreAuthorizedAmountResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\MallDeferredCaptureHistoryException;
use Transbank\Webpay\Exceptions\MallIncreaseAmountException;
use Transbank\Webpay\Exceptions\MallIncreaseAuthorizationDateException;
use Transbank\Webpay\Exceptions\MallReversePreAuthorizedAmountException;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Oneclick;
use Transbank\Webpay\Oneclick\Exceptions\MallRefundTransactionException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionAuthorizeException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\Options;

class MallTransaction
{
    use InteractsWithWebpayApi;
    const AUTHORIZE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.3/transactions';
    const TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.3/transactions/{buy_order}';
    const TRANSACTION_REFUND_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.3/transactions/{buy_order}/refunds';
    const TRANSACTION_CAPTURE_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.3/transactions/capture';
    const ENDPOINT_INCREASE_AMOUNT = 'rswebpaytransaction/api/oneclick/v1.3/transactions/amount';
    const ENDPOINT_INCREASE_AUTHORIZATION_DATE = 'rswebpaytransaction/api/oneclick/v1.3/transactions/authorization_date';
    const ENDPOINT_REVERSE_PRE_AUTHORIZE_AMOUNT = 'rswebpaytransaction/api/oneclick/v1.3/transactions/reverse/amount';
    const ENDPOINT_DEFERRED_CAPTURE_HISTORY = '/rswebpaytransaction/api/oneclick/v1.3/transactions/details';

    public function authorize(
        $userName,
        $tbkUser,
        $parentBuyOrder,
        $details
    ) {
        $payload = [
            'username'  => $userName,
            'tbk_user'  => $tbkUser,
            'buy_order' => $parentBuyOrder,
            'details'   => $details,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                static::AUTHORIZE_TRANSACTION_ENDPOINT,
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallTransactionAuthorizeException::raise($e);
        }

        return new MallTransactionAuthorizeResponse($response);
    }

    public function capture($childCommerceCode, $childBuyOrder, $authorizationCode, $amount)
    {
        $payload = [
            'commerce_code'      => $childCommerceCode,
            'buy_order'          => $childBuyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $amount,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                static::TRANSACTION_CAPTURE_ENDPOINT,
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallTransactionCaptureException::raise($e);
        }

        return new MallTransactionCaptureResponse($response);
    }

    public function status($buyOrder)
    {
        try {
            $response = $this->sendRequest(
                'GET',
                str_replace('{buy_order}', $buyOrder, static::TRANSACTION_STATUS_ENDPOINT),
                null
            );
        } catch (WebpayRequestException $e) {
            throw MallTransactionStatusException::raise($e);
        }

        return new MallTransactionStatusResponse($response);
    }

    public function refund($buyOrder, $childCommerceCode, $childBuyOrder, $amount, $options = null)
    {
        $payload = [
            'detail_buy_order' => $childBuyOrder,
            'commerce_code'    => $childCommerceCode,
            'amount'           => $amount,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                str_replace('{buy_order}', $buyOrder, static::TRANSACTION_REFUND_ENDPOINT),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallRefundTransactionException::raise($e);
        }

        return new MallTransactionRefundResponse($response);
    }

    /**
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
    public function increaseAmount($buyOrder, $authorizationCode, $amount, $commerceCode)
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
                static::ENDPOINT_INCREASE_AMOUNT,
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
    public function increaseAuthorizationDate($buyOrder, $authorizationCode, $commerceCode)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'commerce_code'      => $commerceCode,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                static::ENDPOINT_INCREASE_AUTHORIZATION_DATE,
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
    public function reversePreAuthorizedAmount($buyOrder, $authorizationCode, $amount, $commerceCode)
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
                static::ENDPOINT_REVERSE_PRE_AUTHORIZE_AMOUNT,
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
    public function deferredCaptureHistory($authorizationCode, $buyOrder, $commerceCode)
    {
        $payload = [
            'authorization_code'  => $authorizationCode,
            'buy_order'          => $buyOrder,
            'commerce_code'      => $commerceCode,
        ];

        try {
            $response = $this->sendRequest(
                'POST',
                static::ENDPOINT_DEFERRED_CAPTURE_HISTORY,
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw MallDeferredCaptureHistoryException::raise($e);
        }

        return new MallDeferredCaptureHistoryResponse($response);
    }

    public static function getDefaultOptions()
    {
        return Options::forIntegration(Oneclick::DEFAULT_COMMERCE_CODE);
    }

    public static function getGlobalOptions()
    {
        return Oneclick::getOptions();
    }
}
