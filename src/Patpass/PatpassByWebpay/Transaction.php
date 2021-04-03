<?php

namespace Transbank\Patpass\PatpassByWebpay;

use Transbank\Patpass\PatpassByWebpay\Exceptions\TransactionCommitException;
use Transbank\Patpass\PatpassByWebpay\Exceptions\TransactionCreateException;
use Transbank\Patpass\PatpassByWebpay\Exceptions\TransactionStatusException;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionCommitResponse;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionCreateResponse;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionStatusResponse;
use Transbank\Webpay\Exceptions\WebpayRequestException;

class Transaction
{
    const CREATE_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions';
    const COMMIT_TRANSACTION_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';
    const GET_TRANSACTION_STATUS_ENDPOINT = 'rswebpaytransaction/api/webpay/v1.2/transactions/{token}';

    public function create($buyOrder, $sessionId, $amount, $returnUrl, $details)
    {
        $payload = [
            'buy_order'  => $buyOrder,
            'session_id' => $sessionId,
            'amount'     => $amount,
            'return_url' => $returnUrl,
            'wpm_detail' => $details,
        ];

        try {
            $response = $this->sendRequest('POST', self::CREATE_TRANSACTION_ENDPOINT, $payload);
        } catch (WebpayRequestException $exception) {
            throw TransactionCreateException::raise($exception);
        }
        return new TransactionCreateResponse($response);

    }

    public function commit($token)
    {
        $endpoint = str_replace('{token}', $token, self::COMMIT_TRANSACTION_ENDPOINT);
      try {
        $response = $this->sendRequest('PUT', $endpoint, null);
      } catch (WebpayRequestException $exception) {
          throw TransactionCommitException::raise($exception);
      }

      return new TransactionCommitResponse($response);
    }

    public function getStatus($token)
    {
        $endpoint = str_replace('{token}', $token, self::GET_TRANSACTION_STATUS_ENDPOINT);
        try {
            $response = $this->sendRequest('GET', $endpoint, null);
        } catch (WebpayRequestException $exception) {
            throw TransactionStatusException::raise($exception);
        }

        return new TransactionStatusResponse($response);

    }
}
