<?php

namespace Transbank\Webpay\Oneclick;

use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Oneclick;
use Transbank\Webpay\Oneclick\Exceptions\AuthorizeMallTransactionException;
use Transbank\Webpay\Oneclick\Exceptions\MallRefundTransactionException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionStatusResponse;

class MallTransaction
{
    use InteractsWithWebpayApi;
    const AUTHORIZE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions';
    const TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/{buy_order}';
    const TRANSACTION_REFUND_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/{buy_order}/refunds';
    const TRANSACTION_CAPTURE_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/capture';

    public static function authorize(
        $userName,
        $tbkUser,
        $parentBuyOrder,
        $details,
        $options = null
    ) {
        $options = Oneclick::getDefaultOptions($options);

        $payload = [
            'username'  => $userName,
            'tbk_user'  => $tbkUser,
            'buy_order' => $parentBuyOrder,
            'details'   => $details,
        ];

        try {
            $response = static::request(
                'POST',
                static::AUTHORIZE_TRANSACTION_ENDPOINT,
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw AuthorizeMallTransactionException::raise($e);
        }

        return new MallTransactionAuthorizeResponse($response);
    }

    public static function capture($childCommerceCode, $childBuyOrder, $authorizationCode, $amount, $options = null)
    {
        $options = Oneclick::getDefaultOptions($options);

        $payload = [
            'commerce_code'      => $childCommerceCode,
            'buy_order'          => $childBuyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $amount,
        ];

        try {
            $response = static::request(
                'PUT',
                static::TRANSACTION_CAPTURE_ENDPOINT,
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw MallTransactionCaptureException::raise($e);
        }

        return new MallTransactionCaptureResponse($response);
    }

    public static function status($buyOrder, $options = null)
    {
        $options = Oneclick::getDefaultOptions($options);

        try {
            $response = static::request(
                'GET',
                str_replace('{buy_order}', $buyOrder, self::TRANSACTION_STATUS_ENDPOINT),
                null,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw MallTransactionStatusException::raise($e);
        }

        return new MallTransactionStatusResponse($response);
    }

    public static function refund($buyOrder, $childCommerceCode, $childBuyOrder, $amount, $options = null)
    {
        $options = Oneclick::getDefaultOptions($options);
        $payload = [
            'detail_buy_order' => $childBuyOrder,
            'commerce_code'    => $childCommerceCode,
            'amount'           => $amount,
        ];

        try {
            $response = static::request(
                'POST',
                str_replace('{buy_order}', $buyOrder, self::TRANSACTION_REFUND_ENDPOINT),
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw MallRefundTransactionException::raise($e);
        }

        return new MallTransactionRefundResponse($response);
    }
}
