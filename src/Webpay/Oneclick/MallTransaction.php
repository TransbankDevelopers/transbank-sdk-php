<?php

namespace Transbank\Webpay\Oneclick;

use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Oneclick\Exceptions\MallRefundTransactionException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionAuthorizeException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionStatusResponse;
use Transbank\Utils\Curl\Exceptions\CurlRequestException;

class MallTransaction
{
    use InteractsWithWebpayApi;
    const AUTHORIZE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions';
    const TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/{buy_order}';
    const TRANSACTION_REFUND_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/{buy_order}/refunds';
    const TRANSACTION_CAPTURE_ENDPOINT = 'rswebpaytransaction/api/oneclick/v1.2/transactions/capture';

    /**
     * @param string $userName
     * @param string $tbkUser
     * @param string $parentBuyOrder
     * @param array  $details
     *
     * @return MallTransactionAuthorizeResponse
     *
     * @throws MallTransactionAuthorizeException
     * @throws CurlRequestException
     */
    public function authorize(
        string $userName,
        string $tbkUser,
        string $parentBuyOrder,
        array $details
    ): MallTransactionAuthorizeResponse {
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
        } catch (WebpayRequestException $exception) {
            throw new MallTransactionAuthorizeException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionAuthorizeResponse($response);
    }

    /**
     * @param string $childCommerceCode
     * @param string $childBuyOrder
     * @param string $authorizationCode
     * @param int|float  $amount
     *
     * @return MallTransactionCaptureResponse
     *
     * @throws MallTransactionCaptureException
     * @throws CurlRequestException
     */
    public function capture(
        string $childCommerceCode,
        string $childBuyOrder,
        string $authorizationCode,
        int|float $amount
    ): MallTransactionCaptureResponse {
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

    /**
     * @param string $buyOrder
     *
     * @return MallTransactionStatusResponse
     *
     * @throws MallTransactionStatusException
     * @throws CurlRequestException
     */
    public function status(string $buyOrder): MallTransactionStatusResponse
    {
        try {
            $response = $this->sendRequest(
                'GET',
                str_replace('{buy_order}', $buyOrder, static::TRANSACTION_STATUS_ENDPOINT),
                []
            );
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
     * @param string $buyOrder
     * @param string $childCommerceCode
     * @param string $childBuyOrder
     * @param int|float $amount
     *
     * @return MallTransactionRefundResponse
     *
     * @throws MallRefundTransactionException
     * @throws CurlRequestException
     */
    public function refund(
        string $buyOrder,
        string $childCommerceCode,
        string $childBuyOrder,
        int|float $amount
    ): MallTransactionRefundResponse {
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
        } catch (WebpayRequestException $exception) {
            throw new MallRefundTransactionException(
                $exception->getMessage(),
                $exception->getTransbankErrorMessage(),
                $exception->getHttpCode(),
                $exception->getFailedRequest(),
                $exception
            );
        }

        return new MallTransactionRefundResponse($response);
    }
}
