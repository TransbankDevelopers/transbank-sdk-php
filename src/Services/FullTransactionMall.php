<?php

namespace Transbank\Sdk\Services;

use DateTime;
use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Credentials\Container;
use Transbank\Sdk\Transbank;

class FullTransactionMall
{
    use FiresEvents;
    use DebugsTransactions;
    use SendsRequests;

    // Services names.
    protected const SERVICE_NAME = 'fullTransactionMall';
    protected const ACTION_CREATE = self::SERVICE_NAME . '.create';
    protected const ACTION_INSTALLMENTS = self::SERVICE_NAME . '.installments';
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
    public const ENDPOINT_INSTALLMENTS = self::ENDPOINT_STATUS . '/installments';

    /**
     * Transbank instance.
     *
     * @var \Transbank\Sdk\Transbank
     */
    protected $transbank;

    /**
     * Credential Container instance.
     *
     * @var \Transbank\Sdk\Credentials\Container
     */
    protected $container;

    /**
     * Webpay constructor.
     *
     * @param  \Transbank\Sdk\Transbank  $transbank
     * @param  \Transbank\Sdk\Credentials\Container  $container
     */
    public function __construct(Transbank $transbank, Container $container)
    {
        $this->container = $container;
        $this->transbank = $transbank;
    }

    /**
     * Creates a new transaction.
     *
     * @param  string  $buyOrder
     * @param  string  $sessionId
     * @param  int|string  $cardNumber
     * @param  string|\DateTime  $expiration
     * @param  array  $details
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Response
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function create(
        string $buyOrder,
        string $sessionId,
        $cardNumber,
        $expiration,
        array $details,
        array $options = []
    ): Transactions\Response {
        if ($expiration instanceof DateTime) {
            $expiration = $expiration->format('y/m');
        }

        $apiRequest = new ApiRequest(
            static::ACTION_CREATE,
            [
                'buy_order' => $buyOrder,
                'session_id' => $sessionId,
                'card_number' => $cardNumber,
                'card_expiration_date' => $expiration,
                'details' => $details,
            ]
        );

        $this->log('Creating transaction', ['api_request' => $apiRequest]);

        $this->fireStarted($apiRequest);

        $response = $this->send(
            static::ACTION_CREATE,
            $apiRequest,
            'post',
            static::ENDPOINT_CREATE,
            [],
            $options
        );

        $this->logResponse(['api_request' => $apiRequest, 'response' => $response]);

        return new Transactions\Response($response['token'], $response['url']);
    }

    /**
     * Retrieves the installments for a transaction.
     *
     * @param  string|int  $commerceCode
     * @param  string  $token
     * @param  string  $buyOrder
     * @param  int  $installments
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function installments(
        $commerceCode,
        string $token,
        string $buyOrder,
        int $installments,
        array $options = []
    ): Transactions\Transaction {
        $apiRequest = new ApiRequest(
            static::ACTION_INSTALLMENTS,
            [
                'commerce_code' => $commerceCode,
                'installments_number' => $installments,
                'buy_order' => $buyOrder,
            ]
        );

        $this->log('Retrieving installments', ['token' => $token, 'api_request' => $apiRequest]);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'post', self::ENDPOINT_INSTALLMENTS, [], $options);

        $this->logResponse(['token' => $token, 'api_request' => $apiRequest, 'response' => $response]);

        return new Transactions\Transaction(static::ACTION_INSTALLMENTS, $response);
    }

    /**
     * Commits a transaction.
     *
     * @param  array  $details
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function commit(array $details, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_COMMIT, ['details' => $details]);

        $this->log('Commiting transaction', ['api_request' => $apiRequest]);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'put', self::ENDPOINT_COMMIT, [], $options);

        $this->logResponse(['api_request' => $apiRequest, 'response' => $response]);

        $this->fireCompleted($apiRequest, $response);

        return Transactions\Transaction::createWithDetails(static::ACTION_COMMIT, $response);
    }

    /**
     * Retrieves a transaction status.
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

        $this->log('Retrieving transaction status', ['token' => $token, 'api_request' => $apiRequest]);

        $response = $this->send(
            static::SERVICE_NAME,
            $apiRequest,
            'get',
            self::ENDPOINT_STATUS,
            ['{token}' => $token],
            $options
        );

        $this->logResponse(['token' => $token, 'api_request' => $apiRequest, 'response' => $response]);

        return Transactions\Transaction::createWithDetails(static::ACTION_COMMIT, $response);
    }

    /**
     * Refunds a transaction partially or completely.
     *
     * @param  string  $commerceCode
     * @param  string  $token
     * @param  string  $buyOrder
     * @param  int|float|null  $amount
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function refund(
        string $commerceCode,
        string $token,
        string $buyOrder,
        $amount,
        array $options = []
    ): Transactions\Transaction {
        $apiRequest = new ApiRequest(
            static::ACTION_REFUND,
            [
                'commerce_code' => $commerceCode,
                'amount' => $amount,
                'buy' => $buyOrder,
            ]
        );

        $this->log('Refunding transaction', ['token' => $token, 'api_request' => $apiRequest]);

        $this->fireStarted($apiRequest);

        $response = $this->send(
            static::SERVICE_NAME,
            $apiRequest,
            'post',
            self::ENDPOINT_REFUND,
            ['{token}' => $token],
            $options
        );

        $this->logResponse(['token' => $token, 'api_request' => $apiRequest, 'response' => $response]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_REFUND, $response);
    }

    /**
     * Capture a transaction.
     *
     * @param  string  $commerceCode
     * @param  string  $token
     * @param  string  $buyOrder
     * @param  int  $authorizationCode
     * @param  int|float  $captureAmount
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function capture(
        string $commerceCode,
        string $token,
        string $buyOrder,
        int $authorizationCode,
        $captureAmount,
        array $options = []
    ): Transactions\Transaction {
        $apiRequest = new ApiRequest(
            static::ACTION_CAPTURE,
            [
                'commerce_code' => $commerceCode,
                'buy_order' => $buyOrder,
                'authorization_code' => $authorizationCode,
                'capture_amount' => $captureAmount,
            ]
        );

        $this->log('Capturing transaction', ['token' => $token, 'api_request' => $apiRequest]);

        // If we are on integration, we need to override the credentials.
        $serviceName = $this->transbank->isIntegration() ? static::ACTION_CAPTURE : static::SERVICE_NAME;

        $response = $this->send(
            $serviceName,
            $apiRequest,
            'put',
            static::ENDPOINT_CAPTURE,
            [
                '{token}' => $token,
            ],
            $options
        );

        $this->logResponse(['token' => $token, 'api_request' => $apiRequest, 'response' => $response]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_CAPTURE, $response);
    }
}
