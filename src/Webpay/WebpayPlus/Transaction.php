<?php

namespace Transbank\Webpay\WebpayPlus;

use GuzzleHttp\Exception\GuzzleException;
use Transbank\Utils\HttpClient;
use Transbank\Utils\InteractsWithWebpayApi;
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
    
    const DEFAULT_COMMERCE_CODE = '597055555532';
    const DEFAULT_API_KEY = Options::DEFAULT_API_KEY;

    const ENDPOINT_CREATE_TRANSACTION = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const ENDPOINT_COMMIT_TRANSACTION = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_REFUND_TRANSACTION = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const ENDPOINT_STATUS_TRANSACTION = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_CAPTURE_TRANSACTION = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';
    
    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param int $amount
     * @param string $returnUrl
     * @return TransactionCreateResponse
     *
     * @throws TransactionCreateException
     * @throws GuzzleException
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
            $response = $this->request(
                'POST',
                static::ENDPOINT_CREATE_TRANSACTION,
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }

        return new TransactionCreateResponse($response);
    }
    
    /**
     * @param $token
     * @return TransactionCommitResponse
     * @throws TransactionCommitException
     * @throws GuzzleException
     */
    public function commit($token)
    {
        try {
            $response = $this->request(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_COMMIT_TRANSACTION),
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
     * @return TransactionRefundResponse
     * @throws TransactionRefundException
     * @throws GuzzleException
     */
    public function refund($token, $amount)
    {
        try {
            $response = $this->request(
                'POST',
                str_replace('{token}', $token, static::ENDPOINT_REFUND_TRANSACTION),
                ['amount' => $amount]
            );
        } catch (WebpayRequestException $e) {
            throw TransactionRefundException::raise($e);
        }

        return new TransactionRefundResponse($response);
    }
    
    /**
     * @param $token
     * @return TransactionStatusResponse
     * @throws TransactionStatusException
     * @throws GuzzleException
     */
    public function status($token)
    {
        try {
            $response = $this->request(
                'GET',
                str_replace('{token}', $token, static::ENDPOINT_STATUS_TRANSACTION),
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
     * @return TransactionCaptureResponse
     * @throws TransactionCaptureException
     * @throws GuzzleException
     */
    public function capture($token, $buyOrder, $authorizationCode, $captureAmount)
    {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $captureAmount,
        ];

        try {
            $response = $this->request(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_CAPTURE_TRANSACTION),
                $payload
            );
        } catch (WebpayRequestException $e) {
            throw TransactionCaptureException::raise($e);
        }

        return new TransactionCaptureResponse($response);
    }
    
    /**
     * Get the default options if none are given.
     *
     * @return Options
     */
    public function getDefaultOptions()
    {
        return Options::forIntegration(static::DEFAULT_API_KEY, static::DEFAULT_COMMERCE_CODE);
    }
    
}
