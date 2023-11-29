<?php

namespace Transbank\Webpay\Oneclick;

use Transbank\Utils\InteractsWithWebpayApi;
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
    const AUTHORIZE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions';
    const TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/{buy_order}';
    const TRANSACTION_REFUND_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/{buy_order}/refunds';
    const TRANSACTION_CAPTURE_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/capture';

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

    public static function getDefaultOptions()
    {
        return Options::forIntegration(Oneclick::DEFAULT_COMMERCE_CODE);
    }

    public static function getGlobalOptions()
    {
        return Oneclick::getOptions();
    }
}
