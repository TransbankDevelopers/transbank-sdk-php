<?php

namespace Tests\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Tests\FormatsToCamelCase;
use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Credentials\Container;
use Transbank\Sdk\Credentials\Credentials;
use Transbank\Sdk\Events\TransactionCompleted;
use Transbank\Sdk\Events\TransactionCreating;
use Transbank\Sdk\Http\Connector;
use Transbank\Sdk\Services\Webpay;
use Transbank\Sdk\Transbank;

class WebpayTest extends TestCase
{
    use FormatsToCamelCase;
    use AssertsApiEndpoint;

    protected Transbank $transbank;
    protected HandlerStack $handlerStack;
    protected LoggerInterface $logger;
    protected EventDispatcherInterface $dispatcher;
    /** @var array<array<\GuzzleHttp\Psr7\ServerRequest>> */
    protected array $requests;

    protected function setUp(): void
    {
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->dispatcher = Mockery::mock(EventDispatcherInterface::class);

        $this->requests = [];
        $this->handlerStack = HandlerStack::create();
        $this->handlerStack->push(Middleware::history($this->requests));

        $connector = new Connector(new Client([
            'handler' => $this->handlerStack,
        ]), $factory = new Psr17Factory, $factory);

        $this->transbank = new Transbank(new Container, $this->logger, $this->dispatcher, $connector);
    }

    public function test_uses_production_credentials(): void
    {
        $this->transbank->connector = Mockery::mock(Connector::class);
        $this->transbank->connector->shouldReceive('send')->withArgs(
            function(string $method, string $endpoint, ApiRequest $apiRequest, Credentials $credentials) {
                static::assertEquals('test_key', $credentials->key);
                static::assertEquals('test_secret', $credentials->secret);
                return true;
            }
        )->times(5)->andReturn(['token' => 'test_token', 'url' => 'test_url']);

        $this->logger->shouldReceive('debug')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();
        $this->dispatcher->shouldReceive('dispatch')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();

        $this->transbank->toProduction([
            'webpay' => ['key' => 'test_key', 'secret' => 'test_secret']
        ]);

        $this->transbank->webpay()->create('test_buy_order', 100, 'test_return_url', 'test_session_id');
        $this->transbank->webpay()->status('test_token');
        $this->transbank->webpay()->commit('test_token');
        $this->transbank->webpay()->refund('test_token', 1000);
        $this->transbank->webpay()->capture('test_token', 'test_buy_order', '1234', 1000);
    }

    public function test_uses_integration_credentials_by_default(): void
    {
        $this->transbank->connector = Mockery::mock(Connector::class);
        $this->transbank->connector->shouldReceive('send')->withArgs(
            function(string $method, string $endpoint, ApiRequest $apiRequest, Credentials $credentials) {
                static::assertEquals(Credentials::INTEGRATION_KEYS['webpay'], $credentials->key);
                static::assertEquals(Credentials::INTEGRATION_SECRET, $credentials->secret);
                return true;
            }
        )->times(5)->andReturn(['token' => 'test_token', 'url' => 'test_url']);

        $this->logger->shouldReceive('debug')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();
        $this->dispatcher->shouldReceive('dispatch')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();

        $this->transbank->webpay()->create('test_buy_order', 100, 'test_return_url', 'test_session_id');
        $this->transbank->webpay()->status('test_token');
        $this->transbank->webpay()->commit('test_token');
        $this->transbank->webpay()->refund('test_token', 1000);
        $this->transbank->webpay()->capture('test_token', 'test_buy_order', '1234', 1000);
    }

    public function test_create(): void
    {
        $buyOrder = 'test-buyOrder';
        $amount = 100;
        $returnUrl = 'http://app.com/return';
        $sessionId = 'test_session_id';

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode([
                'token' => $token = '01ab1cc073c91fe5fc08a1b3b00ac3f63033a0e3dbdfdb1fde55c044ed8161b6',
                'url' => $url = 'https://webpay3g.transbank.cl/webpayserver/initTransaction',
            ], JSON_THROW_ON_ERROR)),
        ]));

        $this->dispatcher->shouldReceive('dispatch', function(TransactionCreating $event) use ($buyOrder, $amount, $returnUrl, $sessionId) {
            return 'webpay.create' === $event->apiRequest->serviceAction
                && $buyOrder === $event->apiRequest['buy_order']
                && $amount === $event->apiRequest['amount']
                && $returnUrl === $event->apiRequest['return_url']
                && $sessionId === $event->apiRequest['session_id'];
        });

        $this->logger->shouldReceive('debug', function(string $action, array $context) use ($buyOrder, $amount, $returnUrl, $sessionId) {
            return $action === 'Creating transaction'
                && $buyOrder === $context['api_request']['buy_order']
                && $amount === $context['api_request']['amount']
                && $returnUrl === $context['api_request']['return_url']
                && $sessionId === $context['api_request']['session_id'];
        });

        $this->logger->shouldReceive('debug', function(string $action, array $context) use ($buyOrder, $amount, $returnUrl, $sessionId, $token, $url) {
            return $action === 'Received response'
                && $buyOrder === $context['api_request']['buy_order']
                && $amount === $context['api_request']['amount']
                && $returnUrl === $context['api_request']['return_url']
                && $sessionId === $context['api_request']['session_id']
                && $token === $context['response']['token']
                && $url === $context['response']['url'];
        });

        $response = $this->transbank->webpay()->create($buyOrder, $amount, $returnUrl, $sessionId, []);

        static::assertEquals($response->getToken(), $token);
        static::assertEquals($response->getUrl(), $url);

        static::assertCount(1, $this->requests);
        static::assertEquals('POST', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(Webpay::ENDPOINT_CREATE, $this->requests[0]['request']);
    }

    public function test_commit(): void
    {
        $token = '01abd1b55849b31783b352ebcb6adaf1f7d0dab7476aac499568c01585c5e289';

        $transbankResponse = [
            'vci' => 'TSY',
            'amount' => 10000,
            'status' => 'AUTHORIZED',
            'buy_order' => 'test_buy_order',
            'session_id' => 'test_session',
            'card_detail' => [
                'card_number' => '6623',
            ],
            'accounting_date' => '0324',
            'transaction_date' => '2021-01-24T22:16:48.562Z',
            'authorization_code' => '1213',
            'payment_type_code' => 'VN',
            'response_code' => 0,
            'installments_number' => 0,
        ];

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode($transbankResponse, JSON_THROW_ON_ERROR)),
        ]));

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($token) {
            return $action === 'Committing transaction'
                && $token === $context['token']
                && 'webpay.commit' === $context['api_request']->serviceAction;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($transbankResponse, $token) {
            return $action === 'Response received'
                && $token === $context['token']
                && 'webpay.commit' === $context['api_request']->serviceAction
                && $transbankResponse === $context['response'];
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCompleted $event) use ($transbankResponse) {
            return $event->apiRequest->serviceAction === 'webpay.commit'
                && $event->response === $transbankResponse;
        })->once()->andReturnNull();


        $response = $this->transbank->webpay()->commit($token);

        static::assertEquals('webpay.commit', $response->serviceAction);

        foreach ($transbankResponse as $key => $value) {
            static::assertEquals($value, $response->{'get' . static::snakeCaseToPascalCase($key)}());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('PUT', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(Webpay::ENDPOINT_COMMIT, $this->requests[0]['request'], [
            '{token}' => $token
        ]);
    }

    public function test_status(): void
    {
        $token = '01abd1b55849b31783b352ebcb6adaf1f7d0dab7476aac499568c01585c5e289';

        $transbankResponse = [
            'vci' => 'TSY',
            'amount' => 10000,
            'status' => 'INITIALIZED',
            'buy_order' => 'test_buy_order',
            'session_id' => 'test_session',
            'card_detail' => [
                'card_number' => '6623',
            ],
            'accounting_date' => '0324',
            'transaction_date' => '2021-01-24T22:16:48.562Z',
            'payment_type_code' => 'VN',
            'installments_number' => 0,
        ];

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode($transbankResponse, JSON_THROW_ON_ERROR)),
        ]));

        $this->dispatcher->shouldNotReceive('dispatch');

        $this->logger->shouldReceive('debug', function(string $action, array $context) use ($token) {
            return $action === 'Transaction status'
                && $token === $context['token']
                && 'webpay.status' === $context['api_request']->serviceAction;
        });

        $this->logger->shouldReceive('debug', function(string $action, array $context) use ($token, $transbankResponse) {
            return $action === 'Response received'
                && $token === $context['token']
                && 'webpay.status' === $context['api_request']->serviceAction
                && $transbankResponse === $context['response'];
        });

        $response = $this->transbank->webpay()->status($token);

        static::assertEquals('webpay.status', $response->serviceAction);

        foreach ($transbankResponse as $key => $value) {
            static::assertEquals($value, $response->{'get' . static::snakeCaseToPascalCase($key)}());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('GET', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(Webpay::ENDPOINT_STATUS, $this->requests[0]['request'], [
            '{token}' => $token
        ]);
    }

    public function test_refund(): void
    {
        $token = '01abd1b55849b31783b352ebcb6adaf1f7d0dab7476aac499568c01585c5e289';

        $transbankResponse = [
            'type' => 'NULLIFIED',
            'authorization_code' => '123456',
            'authorization_date' => '2019-03-20T20:18:20Z',
            'nullified_amount' => $nullifiedAmount = 1000.00,
            'balance' => 0.00,
            'response_code' => 0,
        ];

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCreating $event) use ($nullifiedAmount) {
            return $event->apiRequest->serviceAction === 'webpay.refund'
                && $event->apiRequest['amount'] === $nullifiedAmount;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCompleted $event) use ($transbankResponse, $nullifiedAmount) {
            return $event->apiRequest->serviceAction === 'webpay.refund'
                && $event->apiRequest['amount'] === $nullifiedAmount
                && $event->response == $transbankResponse;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($nullifiedAmount, $token) {
            return $action === 'Refunding transaction'
                && $token === $context['token']
                && 'webpay.refund' === $context['api_request']->serviceAction
                && $nullifiedAmount === $context['api_request']['amount'];
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($transbankResponse, $nullifiedAmount, $token) {
            return $action === 'Response received'
                && $token === $context['token']
                && 'webpay.refund' === $context['api_request']->serviceAction
                && $nullifiedAmount === $context['api_request']['amount']
                && $transbankResponse == $context['response'];
        })->once()->andReturnNull();

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode($transbankResponse, JSON_THROW_ON_ERROR)),
        ]));

        $response = $this->transbank->webpay()->refund($token, $nullifiedAmount);

        static::assertEquals($response->getNullifiedAmount(), $nullifiedAmount);
        static::assertTrue($response->isSuccessful());

        foreach ($transbankResponse as $key => $value) {
            static::assertEquals($value, $response->{'get' . static::snakeCaseToPascalCase($key)}());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('PUT', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(Webpay::ENDPOINT_REFUND, $this->requests[0]['request'], [
            '{token}' => $token
        ]);
    }

    public function test_capture(): void
    {
        $buyOrder = 'test_buy_order';
        $authorizationCode = '123456';
        $captureAmount = 1000;
        $token = 'e074d38c628122c63e5c0986368ece22974d6fee1440617d85873b7b4efa48a3';

        $transbankResponse = [
            'token' => $token,
            'authorization_code' => $authorizationCode,
            'authorization_date' => '2019-03-20T20:18:20Z',
            'captured_amount' => $captureAmount,
            'response_code' => 0,
        ];

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCompleted $event) use ($captureAmount, $authorizationCode, $buyOrder, $transbankResponse) {
            return 'webpay.capture' === $event->apiRequest->serviceAction
                && $event->apiRequest['buy_order'] === $buyOrder
                && $event->apiRequest['authorization_code'] == $authorizationCode
                && $event->apiRequest['capture_amount'] == $captureAmount
                && $transbankResponse === $event->response;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($token) {
            return $action === 'Capturing transaction'
                && $token === $context['token'];
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($transbankResponse, $token) {
            return $action === 'Response received'
                && $token === $context['token']
                && $transbankResponse === $context['response'];
        })->once()->andReturnNull();

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode($transbankResponse, JSON_THROW_ON_ERROR)),
        ]));

        $response = $this->transbank->webpay()->capture($token, $buyOrder, $authorizationCode, $captureAmount);

        static::assertEquals($response->getToken(), $token);
        static::assertEquals($response->getCapturedAmount(), $captureAmount);
        static::assertEquals($response->getAuthorizationCode(), $authorizationCode);
        static::assertTrue($response->isSuccessful());

        static::assertCount(1, $this->requests);
        static::assertEquals('PUT', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(Webpay::ENDPOINT_CAPTURE, $this->requests[0]['request'], [
            '{token}' => $token
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
