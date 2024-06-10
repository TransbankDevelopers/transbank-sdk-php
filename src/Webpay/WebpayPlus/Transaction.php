<?php

namespace Transbank\Webpay\WebpayPlus;

use Transbank\Utils\InteractsWithWebpayApi;
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
 */
class Transaction
{
    use InteractsWithWebpayApi;

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';

    /**
     * @param string $buyOrder
     * @param string $sessionId
     * @param int    $amount
     * @param string $returnUrl
     *
     * @throws TransactionCreateException
     * @throws GuzzleHttp\Exception\GuzzleException
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
            throw new TransactionCreateException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionCreateResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionCommitException
     * @throws GuzzleHttp\Exception\GuzzleException
     *
     * @return TransactionCommitResponse
     */
    public function commit($token)
    {
        if (!is_string($token)) {
            throw new \InvalidArgumentException('Token parameter given is not string.');
        }
        if (!isset($token) || trim($token) === '') {
            throw new \InvalidArgumentException('Token parameter given is empty.');
        }

        try {
            $response = $this->sendRequest(
                'PUT',
                str_replace('{token}', $token, static::ENDPOINT_COMMIT),
                null
            );
        } catch (WebpayRequestException $exception) {
            throw new TransactionCommitException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionCommitResponse($response);
    }

    /**
     * @param $token
     * @param $amount
     *
     * @throws TransactionRefundException
     * @throws GuzzleHttp\Exception\GuzzleException
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
        } catch (WebpayRequestException $exception) {
            throw new TransactionRefundException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionRefundResponse($response);
    }

    /**
     * @param $token
     *
     * @throws TransactionStatusException
     * @throws GuzzleHttp\Exception\GuzzleException
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
        } catch (WebpayRequestException $exception) {
            throw new TransactionStatusException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
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
     * @throws GuzzleHttp\Exception\GuzzleException
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
        } catch (WebpayRequestException $exception) {
            throw new TransactionCaptureException($exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionCaptureResponse($response);
    }

}
