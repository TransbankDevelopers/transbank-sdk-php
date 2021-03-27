<?php

namespace Transbank\Sdk\Services;

use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Credentials\Credentials;
use Transbank\Sdk\Transbank;

class WebpayMall
{
    use FiresEvents;
    use DebugsTransactions;
    use SendsRequests;

    protected const SERVICE_NAME = 'webpayMall';
    protected const ACTION_CREATE = self::SERVICE_NAME . '.create';
    protected const ACTION_COMMIT = self::SERVICE_NAME . '.commit';
    protected const ACTION_STATUS = self::SERVICE_NAME . '.status';
    protected const ACTION_REFUND = self::SERVICE_NAME . '.refund';
    protected const ACTION_CAPTURE = self::SERVICE_NAME . '.capture';

    // Endpoints for the transactions.
    public const ENDPOINT_CREATE = Webpay::ENDPOINT_CREATE;
    public const ENDPOINT_COMMIT = Webpay::ENDPOINT_COMMIT;
    public const ENDPOINT_REFUND = Webpay::ENDPOINT_REFUND;
    public const ENDPOINT_STATUS = Webpay::ENDPOINT_STATUS;
    public const ENDPOINT_CAPTURE = Webpay::ENDPOINT_CAPTURE;

    /**
     * Webpay constructor.
     *
     * @param  \Transbank\Sdk\Transbank  $transbank
     * @param  \Transbank\Sdk\Credentials\Credentials  $credentials
     */
    public function __construct(
        protected Transbank $transbank,
        protected Credentials $credentials,
    ) {
    }

    /**
     * Creates a Webpay Mall transaction.
     *
     * @param  string  $buyOrder
     * @param  string  $returnUrl
     * @param  array  $transactionDetails
     * @param  string|null  $sessionId
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function create(string $buyOrder, string $returnUrl, array $transactionDetails, string $sessionId = null, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_CREATE, [
            'buy_order' => $buyOrder,
            'session_id' => $sessionId,
            'return_url' => $returnUrl,
            'details' => $transactionDetails,
        ]);

        $this->log('Creating transaction', [
            'api_request' => $apiRequest,
        ]);

        $this->fireStarted($apiRequest);

        $response = $this->send(self::SERVICE_NAME, $apiRequest, 'post', static::ENDPOINT_CREATE, [], $options);

        $this->logResponse([
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        return new Transactions\Transaction(static::ACTION_CREATE, $response);
    }

    /**
     * Commits a Mall transaction from Transbank servers.
     *
     * @param  string  $token
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function commit(string $token, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_COMMIT);

        $this->log('Committing transaction', [
            'token' => $token,
            'api_request' => $apiRequest,
        ]);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'put', static::ENDPOINT_COMMIT, [
            '{token}' => $token,
        ], $options);

        $this->log('Response received', [
            'token' => $token,
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_COMMIT, $response);
    }


    /**
     * Returns the transaction status by its token.
     *
     * @param  string  $token
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function status(string $token, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_STATUS);

        $this->log('ApiRequest status', [
            'token' => $token,
            'api_request' => $apiRequest,
        ]);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'get', Webpay::ENDPOINT_STATUS, [
            '{token}' => $token,
        ], $options);

        $this->log('Response received', [
            'token' => $this,
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        return new Transactions\Transaction(static::ACTION_STATUS, $response);
    }

    /**
     * Refunds partially or totally a Mall transaction in Transbank.
     *
     * @param  string|int  $commerceCode
     * @param  string  $token
     * @param  string  $buyOrder
     * @param  int|float  $amount
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function refund(string|int $commerceCode, string $token, string $buyOrder, int|float $amount, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_REFUND, [
            'commerce_code' => $commerceCode,
            'buy_order' => $buyOrder,
            'amount' => $amount,
        ]);

        $this->log('Refunding transaction', [
            'token' => $token,
            'api_request' => $apiRequest,
        ]);

        $this->fireStarted($apiRequest);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'post', Webpay::ENDPOINT_REFUND, [
            '{token}' => $token,
        ], $options);

        $this->logResponse([
            'token' => $token,
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_REFUND, $response);
    }

    /**
     * Captures an amount of a given transaction by its token.
     *
     * @param  string|int  $commerceCode
     * @param  string  $token
     * @param  string  $buyOrder
     * @param  int  $authorizationCode
     * @param  int|float  $captureAmount
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function capture(string|int $commerceCode, string $token, string $buyOrder, int $authorizationCode, int|float $captureAmount, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_CAPTURE, [
            'commerce_code' => $commerceCode,
            'buy_order' => $buyOrder,
            'authorization_code' => $authorizationCode,
            'capture_amount' => $captureAmount,
        ]);

        $this->log('Capturing transaction', [
            'token' => $token,
            'api_request' => $apiRequest,
        ]);

        // If we are on integration, we need to override the credentials.
        $serviceName = $this->transbank->isIntegration() ? static::ACTION_CAPTURE : static::SERVICE_NAME;

        $response = $this->send($serviceName, $apiRequest, 'put', Webpay::ENDPOINT_CAPTURE, [
            '{token}' => $token,
        ], $options);

        $this->logResponse([
            'token' => $token,
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_CAPTURE, $response);
    }
}
