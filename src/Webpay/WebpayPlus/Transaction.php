<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Utils\Curl\Exceptions\CurlRequestException;
use Transbank\Webpay\Exceptions\WebpayRequestException;
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
 * @var string ENDPOINT_CREATE
 * @var string ENDPOINT_COMMIT
 * @var string ENDPOINT_REFUND
 * @var string ENDPOINT_STATUS
 * @var string ENDPOINT_CAPTURE
 * @var string SEARCH_STRING
 */
class Transaction
{
    use InteractsWithWebpayApi;

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';
    const SEARCH_STRING = '{token}';

    /**
     * @param string    $buyOrder
     * @param string    $sessionId
     * @param int|float     $amount
     * @param string    $returnUrl
     *
     * @throws TransactionCreateException
     * @throws CurlRequestException
     *
     * @return TransactionCreateResponse
     */
    public function create(
        string $buyOrder,
        string $sessionId,
        int|float $amount,
        string $returnUrl
    ): TransactionCreateResponse {
        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'amount'     => $amount,
            'return_url' => $returnUrl,
        ];

        try {
            $response = $this->sendRequest('POST', static::ENDPOINT_CREATE, $payload);
        } catch (WebpayRequestException $exception) {
            throw new TransactionCreateException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionCreateResponse($response);
    }

    /**
     * @param string $token
     *
     * @throws TransactionCommitException
     * @throws CurlRequestException
     *
     * @return TransactionCommitResponse
     */
    public function commit(string $token): TransactionCommitResponse
    {
        if (!isset($token) || trim($token) === '') {
            throw new \InvalidArgumentException('Token parameter given is empty.');
        }

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_COMMIT),
                []
            );
        } catch (WebpayRequestException $exception) {
            throw new TransactionCommitException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionCommitResponse($response);
    }

    /**
     * @param string $token
     * @param int|float  $amount
     *
     * @throws TransactionRefundException
     * @throws CurlRequestException
     *
     * @return TransactionRefundResponse
     */
    public function refund(string $token, int|float $amount): TransactionRefundResponse
    {
        try {
            $response = $this->sendRequest(
                'POST',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_REFUND),
                ['amount' => $amount]
            );
        } catch (WebpayRequestException $exception) {
            throw new TransactionRefundException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionRefundResponse($response);
    }

    /**
     * @param string $token
     *
     * @throws TransactionStatusException
     * @throws CurlRequestException
     *
     * @return TransactionStatusResponse
     */
    public function status(string $token): TransactionStatusResponse
    {
        try {
            $response = $this->sendRequest(
                'GET',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_STATUS),
                []
            );
        } catch (WebpayRequestException $exception) {
            throw new TransactionStatusException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionStatusResponse($response);
    }

    /**
     * @param string $token
     * @param string $buyOrder
     * @param string $authorizationCode
     * @param int|float  $captureAmount
     *
     * @throws TransactionCaptureException
     * @throws CurlRequestException
     *
     * @return TransactionCaptureResponse
     */
    public function capture(
        string $token,
        string $buyOrder,
        string $authorizationCode,
        int|float $captureAmount
    ): TransactionCaptureResponse {
        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $captureAmount,
        ];

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_CAPTURE),
                $payload
            );
        } catch (WebpayRequestException $exception) {
            throw new TransactionCaptureException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionCaptureResponse($response);
    }
}
