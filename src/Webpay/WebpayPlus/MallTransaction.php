<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Webpay\ConfiguresEnvironment;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\InteractsWithWebpayApi;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionStatusException;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Responses\MallCommitResponseTransaction;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionRefundResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionStatusResponse;

class MallTransaction
{
    use InteractsWithWebpayApi;
    
    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions';
    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/{token}';
    const REFUND_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/{token}/refunds';
    const TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/{token}';
    const CAPTURE_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.0/transactions/{token}/capture';
    
    public static function create(
        $buyOrder,
        $sessionId,
        $returnUrl,
        $details,
        $options = null
    ) {
        $options = WebpayPlus::getDefaultOptions($options);
    
        $payload = [
            'buy_order' => $buyOrder,
            'session_id' => $sessionId,
            'details' => $details,
            'return_url' => $returnUrl
        ];
    
        try {
            $response = static::request(
                'POST',
                static::CREATE_TRANSACTION_ENDPOINT,
                $payload,
                $options
            );
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }
    
        return new TransactionCreateResponse($response);
    }

    public static function commit($token, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);
    
        try {
            $response = static::request(
                'PUT',
                str_replace('{token}', $token, static::COMMIT_TRANSACTION_ENDPOINT),
                null,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionCommitException::raise($e);
        }
    
        return new MallCommitResponseTransaction($response);
    }

    public static function refund($token, $buyOrder, $childCommerceCode, $amount, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);
    
        $payload = [
            'buy_order' => $buyOrder,
            'commerce_code' => $childCommerceCode,
            'amount' => $amount
        ];
        try {
            $response = static::request(
                'POST',
                str_replace('{token}', $token, static::REFUND_TRANSACTION_ENDPOINT),
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionRefundException::raise($e);
        }
    
        return new TransactionRefundResponse($response);
    }

    public static function status($token, $options = null)
    {
        $options = WebpayPlus::getDefaultOptions($options);
    
        try {
            $response = static::request(
                'GET',
                str_replace('{token}', $token, static::TRANSACTION_STATUS_ENDPOINT),
                null,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionStatusException::raise($e);
        }
    
        return new MallTransactionStatusResponse($response);
    }
    
    /**
     * @param $childCommerceCode
     * @param $token
     * @param $buyOrder
     * @param $authorizationCode
     * @param null $captureAmount
     * @param null $options
     * @return TransactionCaptureResponse
     * @throws TransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function capture(
        $childCommerceCode,
        $token,
        $buyOrder,
        $authorizationCode,
        $captureAmount,
        $options = null
    ) {
    
        $options = WebpayPlus::getDefaultOptions($options);
    
        $payload = [
            'commerce_code' => $childCommerceCode,
            'buy_order' => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount' => $captureAmount
        ];
    
        try {
            $response = static::request(
                'PUT',
                str_replace('{token}', $token, static::CAPTURE_ENDPOINT),
                $payload,
                $options
            );
        } catch (WebpayRequestException $e) {
            throw TransactionCaptureException::raise($e);
        }
    
        return new TransactionCaptureResponse($response);
    }
}
