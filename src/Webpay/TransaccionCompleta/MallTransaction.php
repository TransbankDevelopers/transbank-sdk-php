<?php

/**
 * Class MallTransaction.
 *
 * @category
 */

namespace Transbank\Webpay\TransaccionCompleta;

use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionCommitException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionCreateException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionRefundException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionInstallmentsException;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionCommitResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionCreateResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionInstallmentsResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionCaptureResponse;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;

class MallTransaction
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
     * @param string    $cardNumber
     * @param string    $cardExpirationDate
     * @param array     $details
     * @param string|null   $cvv
     *
     * @throws MallTransactionCreateException
     *
     * @return MallTransactionCreateResponse
     */
    public function create(
        string $buyOrder,
        string $sessionId,
        string $cardNumber,
        string $cardExpirationDate,
        array $details,
        string|null $cvv = null
    ) {
        $payload = [
            'buy_order'            => $buyOrder,
            'session_id'           => $sessionId,
            'card_number'          => $cardNumber,
            'card_expiration_date' => $cardExpirationDate,
            'details'              => $details,
        ];
        if ($cvv) {
            $payload['cvv'] = $cvv;
        }

        try {
            $response = $this->sendRequest('POST', static::ENDPOINT_CREATE, $payload);
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCreateException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCreateResponse($response);
    }

    /**
     * @param string $token
     * @param array  $details
     *
     * @throws MallTransactionInstallmentsException
     *
     * @return MallTransactionInstallmentsResponse[]
     */
    public function installments(
        string $token,
        array $details
    ) {
        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_INSTALLMENTS);

        try {
            return array_map(function ($detail) use ($endpoint) {
                $payload = [
                    'commerce_code'       => $detail['commerce_code'],
                    'buy_order'           => $detail['buy_order'],
                    'installments_number' => $detail['installments_number'],
                ];
                $response = $this->sendRequest('POST', $endpoint, $payload);

                return new MallTransactionInstallmentsResponse($response);
            }, $details);
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionInstallmentsException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }
    }

    /**
     * @param string $token
     * @param array  $details
     *
     * @throws MallTransactionCommitException
     *
     * @return MallTransactionCommitResponse
     */
    public function commit(
        $token,
        $details
    ) {
        $payload = [
            'details' => $details,
        ];

        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_COMMIT);

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCommitException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCommitResponse($response);
    }

    /**
     * @param string $token
     * @param string $buyOrder
     * @param string $commerceCodeChild
     * @param int|float  $amount
     *
     * @throws MallTransactionRefundException
     *
     * @return MallTransactionRefundResponse
     */
    public function refund(
        string $token,
        string $buyOrder,
        string $commerceCodeChild,
        int|float $amount
    ) {
        $payload = [
            'buy_order'     => $buyOrder,
            'commerce_code' => $commerceCodeChild,
            'amount'        => $amount,
        ];

        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_REFUND);

        try {
            $response = $this->sendRequest('POST', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionRefundException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionRefundResponse($response);
    }

    /**
     * @param string $token
     *
     * @throws MallTransactionStatusException
     *
     * @return MallTransactionStatusResponse
     */
    public function status(string $token)
    {
        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_STATUS);

        try {
            $response = $this->sendRequest('GET', $endpoint, []);
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionStatusException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionStatusResponse($response);
    }

    /**
     * @param string $token
     * @param string $commerceCode
     * @param string $buyOrder
     * @param string $authorizationCode
     * @param int|float  $captureAmount
     *
     * @throws MallTransactionCaptureException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return MallTransactionCaptureResponse
     */
    public function capture(
        string $token,
        string $commerceCode,
        string $buyOrder,
        string $authorizationCode,
        int|float $captureAmount
    ) {
        $endpoint = str_replace(self::SEARCH_STRING, $token, static::ENDPOINT_CAPTURE);

        $payload = [
            'buy_order'          => $buyOrder,
            'commerce_code'      => $commerceCode,
            'authorization_code' => $authorizationCode,
            'capture_amount'     => $captureAmount,
        ];

        try {
            $response = $this->sendRequest('PUT', $endpoint, $payload);
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionCaptureException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionCaptureResponse($response);
    }
}
