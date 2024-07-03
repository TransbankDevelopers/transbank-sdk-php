<?php

namespace Transbank\Webpay\TransaccionCompleta;

use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCaptureException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCommitException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCreateException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionInstallmentsException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionRefundException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionStatusException;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCommitResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCreateResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionInstallmentsResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionRefundResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionStatusResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCaptureResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Transaction.
 */
class Transaction
{
    use InteractsWithWebpayApi;

    const ENDPOINT_CREATE = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const ENDPOINT_INSTALLMENTS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/installments';
    const ENDPOINT_COMMIT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_REFUND = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/refunds';
    const ENDPOINT_STATUS = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const ENDPOINT_CAPTURE = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}/capture';
    const SEARCH_STRING = '{token}';

    /**
     * @param string    $buyOrder
     * @param string    $sessionId
     * @param float     $amount
     * @param string    $cardNumber
     * @param string    $cardExpirationDate
     * @param string|null   $cvv
     *
     * @throws TransactionCreateException
     * @throws GuzzleException
     *
     * @return TransactionCreateResponse
     */
    public function create(
        string $buyOrder,
        string $sessionId,
        float $amount,
        string $cardNumber,
        string $cardExpirationDate,
        string|null $cvv = null,
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
            throw new TransactionCreateException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode() ?? 0,
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionCreateResponse($response);
    }

    /**
     * @param string $token
     * @param int    $installmentsNumber
     *
     * @throws TransactionInstallmentsException
     * @throws GuzzleException
     *
     * @return TransactionInstallmentsResponse
     */
    public function installments(
        string $token,
        int $installmentsNumber
    ) {
        $payload = [
            'installments_number' => $installmentsNumber,
        ];

        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_INSTALLMENTS);

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw new TransactionInstallmentsException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new TransactionInstallmentsResponse($response);
    }

    /**
     * @param string    $token
     * @param int|null      $idQueryInstallments
     * @param int|null      $deferredPeriodIndex
     * @param bool|null     $gracePeriod
     *
     * @throws TransactionCommitException
     * @throws GuzzleException
     *
     * @return TransactionCommitResponse
     */
    public function commit(
        string $token,
        int|null $idQueryInstallments = null,
        int|null $deferredPeriodIndex = null,
        bool|null  $gracePeriod = null
    ) {
        $payload = [
            'id_query_installments' => $idQueryInstallments,
            'deferred_period_index' => $deferredPeriodIndex,
            'grace_period'          => $gracePeriod,
        ];

        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_COMMIT);

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
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
     * @param float $amount
     *
     * @throws TransactionRefundException
     * @throws GuzzleException
     *
     * @return TransactionRefundResponse
     */
    public function refund(string $token, float $amount)
    {
        $payload = [
            'amount' => $amount,
        ];

        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_REFUND);

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
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
     * @throws GuzzleException
     *
     * @return TransactionStatusResponse
     */
    public function status($token)
    {
        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_STATUS);

        try {
            $response = $this->sendRequest('GET', $endpoint, []);
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
     * @param float $captureAmount
     *
     * @throws TransactionCaptureException
     * @throws GuzzleException
     *
     * @return TransactionCaptureResponse
     */
    public function capture(string $token, string $buyOrder, string $authorizationCode, float $captureAmount)
    {
        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_CAPTURE);

        $payload = [
            'buy_order'          => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $captureAmount,
        ];

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
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
