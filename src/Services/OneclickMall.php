<?php

namespace Transbank\Sdk\Services;

use Transbank\Sdk\ApiRequest;

class OneclickMall
{
    use FiresEvents;
    use DebugsTransactions;
    use SendsRequests;

    // Services names.
    protected const SERVICE_NAME = 'oneclickMall';
    protected const ACTION_START = self::SERVICE_NAME . '.start';
    protected const ACTION_FINISH = self::SERVICE_NAME . '.finish';
    protected const ACTION_DELETE = self::SERVICE_NAME . '.delete';
    protected const ACTION_AUTHORIZE = self::SERVICE_NAME . '.authorize';
    protected const ACTION_STATUS = self::SERVICE_NAME . '.status';
    protected const ACTION_REFUND = self::SERVICE_NAME . '.refund';
    protected const ACTION_CAPTURE = self::SERVICE_NAME . '.capture';

    /**
     * The API base URI.
     *
     * @var string
     */
    protected const ENDPOINT_BASE = '/rswebpaytransaction/api/oneclick/{api_version}/';

    // Endpoints for the inscriptions.
    public const ENDPOINT_START = self::ENDPOINT_BASE . '/inscriptions';
    public const ENDPOINT_FINISH = self::ENDPOINT_BASE . '/inscriptions/{token}';
    public const ENDPOINT_DELETE = self::ENDPOINT_BASE . '/inscriptions';

    // Endpoints for the transactions.
    public const ENDPOINT_AUTHORIZE = self::ENDPOINT_BASE . '/transactions';
    public const ENDPOINT_STATUS = self::ENDPOINT_BASE . '/transactions/{buyOrder}';
    public const ENDPOINT_REFUND = self::ENDPOINT_BASE . '/transactions/{buyOrder}/refunds';
    public const ENDPOINT_CAPTURE = '/rswebpaytransaction/api/oneclick/mall/{api_version}/transactions/capture';

    /**
     * Creates a new pending subscription in Transbank.
     *
     * @param  string  $username
     * @param  string  $email
     * @param  string  $responseUrl
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Response
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function start(string $username, string $email, string $responseUrl, array $options = []): Transactions\Response
    {
        $apiRequest = new ApiRequest(static::ACTION_START, [
            'username' => $username,
            'email' => $email,
            'response_url' => $responseUrl
        ]);

        $this->log('Creating subscription', [
            'api_request' => $apiRequest
        ]);

        $this->fireStarted($apiRequest);

        $response = $this->send(self::SERVICE_NAME, $apiRequest, 'post', static::ENDPOINT_START, [], $options);

        $this->logResponse([
            'api_request' => $apiRequest,
            'response' => $response
        ]);

        return new Transactions\Response($response['token'], $response['url']);
    }

    /**
     * Finishes a subscription process in Transbank.
     *
     * @param  string  $token
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function finish(string $token, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_START);

        $this->log('Finishing subscription process', [
            'token' => $token,
            'api_request' => $apiRequest
        ]);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'put', static::ENDPOINT_FINISH, [
            '{token}' => $token,
        ], $options);

        $this->logResponse([
            'token' => $token,
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_FINISH, $response);
    }


    /**
     * Deletes a subscription.
     *
     * If the subscription doesn't exists, an exception will be returned.
     *
     * @param  string  $tbkUser
     * @param  string  $username
     * @param  array  $options
     *
     * @return void
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function delete(string $tbkUser, string $username, array $options = []): void
    {
        $apiRequest = new ApiRequest(static::ACTION_DELETE, [
            'tbk_user' => $tbkUser,
            'username' => $username,
        ]);

        $this->log('Deleting subscription', [
            'api_request' => $apiRequest
        ]);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'delete', static::ENDPOINT_DELETE, [], $options);

        $this->logResponse([
            'api_request' => $apiRequest,
            'response' => $response,
        ]);
    }

    /**
     * Authorizes a given set of transactions.
     *
     * @param  string  $tbkUser
     * @param  string  $username
     * @param  string  $parentBuyOrder
     * @param  array  $details
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function authorize(string $tbkUser, string $username, string $parentBuyOrder, array $details, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_AUTHORIZE, [
            'tbk_user' => $tbkUser,
            'username' => $username,
            'buy_order' => $username,
            'details' => $details,
        ]);

        $this->log('Authorizing transaction', [
            'api_request' => $apiRequest
        ]);

        $this->fireStarted($apiRequest);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'post', static::ENDPOINT_AUTHORIZE, [], $options);

        $this->logResponse([
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        $this->fireCompleted($apiRequest, $response);

        return Transactions\Transaction::createWithDetails(static::ACTION_AUTHORIZE, $response);
    }

    /**
     * Retrieves a transaction from Transbank.
     *
     * @param  string  $buyOrder
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function status(string $buyOrder, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_STATUS);

        $this->log('Authorizing transaction', [
            'buy_order' => $buyOrder,
            'api_request' => $apiRequest
        ]);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'get', static::ENDPOINT_STATUS, [
            '{buyOrder}' => $buyOrder,
        ], $options);

        $this->logResponse([
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        return Transactions\Transaction::createWithDetails(static::ACTION_AUTHORIZE, $response);
    }

    /**
     * Refunds a child transaction.
     *
     * @param  string  $buyOrder
     * @param  string  $childCommerceCode
     * @param  string  $childBuyOrder
     * @param  int|float  $amount
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function refund(string $buyOrder, string $childCommerceCode, string $childBuyOrder, int|float $amount, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_REFUND, [
            'commerce_code' => $childCommerceCode,
            'detail_buy_order' => $childBuyOrder,
            'amount' => $amount,
        ]);

        $this->log('Refunding transaction', [
            'buy_order' => $buyOrder,
            'api_request' => $apiRequest,
        ]);

        $this->fireStarted($apiRequest);

        $response = $this->send(static::SERVICE_NAME, $apiRequest, 'post', static::ENDPOINT_REFUND, [
            '{buyOrder}' => $buyOrder,
        ], $options);

        $this->logResponse([
            'buy_order' => $buyOrder,
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_REFUND, $response);
    }

    /**
     * Captures a transaction from Transbank.
     *
     * @param  string  $commerceCode
     * @param  string  $buyOrder
     * @param  int  $authorizationCode
     * @param  int|float  $amount
     * @param  array  $options
     *
     * @return \Transbank\Sdk\Services\Transactions\Transaction
     * @throws \Transbank\Sdk\Exceptions\TransbankException
     */
    public function capture(string $commerceCode, string $buyOrder, int $authorizationCode, int|float $amount, array $options = []): Transactions\Transaction
    {
        $apiRequest = new ApiRequest(static::ACTION_CAPTURE, [
            'commerce_code' => $commerceCode,
            'buy_order' => $buyOrder,
            'authorization_code' => $authorizationCode,
            'amount' => $amount,
        ]);

        $this->log('Capturing transaction', [
            'api_request' => $apiRequest,
        ]);

        // If we are on integration, we need to override the credentials.
        $serviceName = $this->transbank->isIntegration() ? static::ACTION_CAPTURE : static::SERVICE_NAME;

        $response = $this->send($serviceName, $apiRequest, 'put', static::ENDPOINT_CAPTURE, [], $options);

        $this->logResponse([
            'api_request' => $apiRequest,
            'response' => $response,
        ]);

        $this->fireCompleted($apiRequest, $response);

        return new Transactions\Transaction(static::ACTION_CAPTURE, $response);
    }
}
