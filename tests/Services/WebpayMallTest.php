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
use Transbank\Sdk\Services\Transactions\Detail;
use Transbank\Sdk\Services\Webpay;
use Transbank\Sdk\Services\WebpayMall;
use Transbank\Sdk\Transbank;

class WebpayMallTest extends TestCase
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
            'webpayMall' => ['key' => 'test_key', 'secret' => 'test_secret']
        ]);

        $this->transbank->webpayMall()->create('test_buy_order', 'test_return_url', [], 'test_session_id');
        $this->transbank->webpayMall()->status('test_token');
        $this->transbank->webpayMall()->commit('test_token');
        $this->transbank->webpayMall()->refund('childCommerceCode', 'test_token', 'test_buy_order', 1000);
        $this->transbank->webpayMall()->capture('childCommerceCode', 'test_token', 'test_buy_order', '1234', 1000);
    }

    public function test_uses_integration_credentials_by_default(): void
    {
        $this->transbank->connector = Mockery::mock(Connector::class);
        $this->transbank->connector->shouldReceive('send')->withArgs(
            function(string $method, string $endpoint, ApiRequest $apiRequest, Credentials $credentials) {
                if ($apiRequest->serviceAction === 'webpayMall.capture') {
                    static::assertEquals(Credentials::INTEGRATION_KEYS['webpayMall.capture'], $credentials->key);
                } else {
                    static::assertEquals(Credentials::INTEGRATION_KEYS['webpayMall'], $credentials->key);
                }
                static::assertEquals(Credentials::INTEGRATION_SECRET, $credentials->secret);
                return true;
            }
        )->times(5)->andReturn(['token' => 'test_token', 'url' => 'test_url']);

        $this->logger->shouldReceive('debug')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();
        $this->dispatcher->shouldReceive('dispatch')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();

        $this->transbank->webpayMall()->create('test_buy_order', 'test_return_url', [], 'test_session_id');
        $this->transbank->webpayMall()->status('test_token');
        $this->transbank->webpayMall()->commit('test_token');
        $this->transbank->webpayMall()->refund('childCommerceCode', 'test_token', 'test_buy_order', 1000);
        $this->transbank->webpayMall()->capture('childCommerceCode', 'test_token', 'test_buy_order', '1234', 1000);
    }

    public function test_create(): void
    {
        $buyOrder = 'test-buyOrder';
        $returnUrl = 'http://app.com/return';
        $sessionId = 'test_session_id';
        $details = [
            [
                "amount" => 10000,
                "commerce_code" => 597055555536,
                "buy_order" => "ordenCompraDetalle1234",
            ],
            [
                "amount" => 12000,
                "commerce_code" => 597055555537,
                "buy_order" => "ordenCompraDetalle4321",
            ],
        ];

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode([
                'token' => $token = '01ab1cc073c91fe5fc08a1b3b00ac3f63033a0e3dbdfdb1fde55c044ed8161b6',
                'url' => $url = 'https://webpay3g.transbank.cl/webpayserver/initTransaction',
            ], JSON_THROW_ON_ERROR)),
        ]));

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($buyOrder, $returnUrl, $details, $sessionId) {
            return $action === 'Creating transaction'
                && $buyOrder === $context['api_request']['buy_order']
                && $details === $context['api_request']['details']
                && $returnUrl === $context['api_request']['return_url']
                && $sessionId === $context['api_request']['session_id'];
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCreating $event) use ($buyOrder, $returnUrl, $details, $sessionId) {
            return 'webpayMall.create' === $event->apiRequest->serviceAction
                && $buyOrder === $event->apiRequest['buy_order']
                && $details === $event->apiRequest['details']
                && $returnUrl === $event->apiRequest['return_url']
                && $sessionId === $event->apiRequest['session_id'];
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($url, $token, $buyOrder, $returnUrl, $details, $sessionId) {
            return $action === 'Response received'
                && $buyOrder === $context['api_request']['buy_order']
                && $details === $context['api_request']['details']
                && $returnUrl === $context['api_request']['return_url']
                && $sessionId === $context['api_request']['session_id']
                && $token === $context['response']['token']
                && $url === $context['response']['url'];
        })->once()->andReturnNull();

        $response = $this->transbank->webpayMall()->create($buyOrder, $returnUrl, $details, $sessionId);

        static::assertEquals($response->getToken(), $token);
        static::assertEquals($response->getUrl(), $url);

        static::assertCount(1, $this->requests);
        static::assertEquals('POST', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(Webpay::ENDPOINT_CREATE, $this->requests[0]['request']);
    }

    public function test_commit(): void
    {
        $token = '01ab1a19c999b0cf2782e59b3dfa8ef4f977e417a2b13aa2cf0276755bf16e5b';

        $transbankResponse = [
            'vci' => 'TSY',
            'details' => [
                [
                    'amount' => 10000,
                    'status' => 'AUTHORIZED',
                    'authorization_code' => '1213',
                    'payment_type_code' => 'VN',
                    'response_code' => 0,
                    'installments_number' => 0,
                    'commerce_code' => '597055555536',
                    'buy_order' => 'ordenCompraDetalle1234',
                ],
                [
                    'amount' => 12000,
                    'status' => 'AUTHORIZED',
                    'authorization_code' => '1213',
                    'payment_type_code' => 'VN',
                    'response_code' => 0,
                    'installments_number' => 0,
                    'commerce_code' => '597055555537',
                    'buy_order' => 'ordenCompraDetalle4321',
                ],
            ],
            'buy_order' => 'ordenCompra12345678',
            'session_id' => 'sesion1234557545',
            'card_detail' => [
                'card_number' => '6623',
            ],
            'accounting_date' => '0325',
            'transaction_date' => '2021-03-25T23:57:57.036Z',
        ];

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode($transbankResponse, JSON_THROW_ON_ERROR)),
        ]));

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCompleted $event) use ($transbankResponse) {
            return $event->apiRequest->serviceAction === 'webpayMall.commit'
                && $event->response === $transbankResponse;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($token) {
            return $action === 'Committing transaction'
                && $token === $context['token']
                && 'webpayMall.commit' === $context['api_request']->serviceAction;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($transbankResponse, $token) {
            return $action === 'Response received'
                && $token === $context['token']
                && 'webpayMall.commit' === $context['api_request']->serviceAction
                && $transbankResponse === $context['response'];
        })->once()->andReturnNull();

        $response = $this->transbank->webpayMall()->commit($token);

        static::assertEquals('webpayMall.commit', $response->serviceAction);

        foreach ($transbankResponse as $key => $value) {
            if ($key !== 'details') {
                static::assertEquals($value, $response->{'get' . static::snakeCaseToPascalCase($key)}());
            }
        }

        foreach ($response->details as $detail) {
            static::assertInstanceOf(Detail::class, $detail);
            static::assertTrue($detail->isSuccessful());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('PUT', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(WebpayMall::ENDPOINT_COMMIT, $this->requests[0]['request'], [
            '{token}' => $token,
        ]);
    }

    public function test_status(): void
    {
        $token = '01abe7ccf59d4f9d099aa881a41c0bcebf4d3557c976a3694051652ff469c3e7';

        $transbankResponse = [
            "vci" => "TSY",
            "details" => [
                [
                    "amount" => 10000,
                    "status" => "INITIALIZED",
                    "payment_type_code" => "VN",
                    "installments_number" => 0,
                    "commerce_code" => "597055555536",
                    "buy_order" => "ordenCompraDetalle1234",
                ],
                [
                    "amount" => 12000,
                    "status" => "INITIALIZED",
                    "payment_type_code" => "VN",
                    "installments_number" => 0,
                    "commerce_code" => "597055555537",
                    "buy_order" => "ordenCompraDetalle4321",
                ],
            ],
            "buy_order" => "ordenCompra12345678",
            "session_id" => "sesion1234557545",
            "card_detail" => [
                "card_number" => "6623",
            ],
            "accounting_date" => "0325",
            "transaction_date" => "2021-02-01T00:49:39.062Z",
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
                && 'webpayMall.status' === $context['api_request']->serviceAction;
        });

        $this->logger->shouldReceive('debug', function(string $action, array $context) use ($token, $transbankResponse) {
            return $action === 'Response received'
                && $token === $context['token']
                && 'webpayMall.status' === $context['api_request']->serviceAction
                && $transbankResponse === $context['response'];
        });

        $response = $this->transbank->webpayMall()->status($token);

        static::assertEquals('webpayMall.status', $response->serviceAction);

        foreach ($transbankResponse as $key => $value) {
            if ($key !== 'details') {
                static::assertEquals($value, $response->{'get' . static::snakeCaseToPascalCase($key)}());
            }
        }

        foreach ($response->details as $detail) {
            static::assertInstanceOf(Detail::class, $detail);
            static::assertFalse($detail->isSuccessful());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('GET', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(WebpayMall::ENDPOINT_STATUS, $this->requests[0]['request'], [
            '{token}' => $token
        ]);
    }

    public function test_refund(): void
    {
        $token = '01abd1b55849b31783b352ebcb6adaf1f7d0dab7476aac499568c01585c5e289';

        $commerceCode = '597055555536';
        $buyOrder = 'ordenCompra12345678';
        $nullifiedAmount = 1000.00;

        $transbankResponse = [
            'type' => 'NULLIFIED',
            'authorization_code' => '123456',
            'authorization_date' => '2019-03-20T20:18:20Z',
            'nullified_amount' => $nullifiedAmount,
            'balance' => 0.00,
            'response_code' => 0,
        ];

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCreating $event) use ($nullifiedAmount) {
            return $event->apiRequest->serviceAction === 'webpayMall.refund'
                && $event->apiRequest['amount'] === $nullifiedAmount;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCompleted $event) use ($transbankResponse, $nullifiedAmount) {
            return $event->apiRequest->serviceAction === 'webpayMall.refund'
                && $event->apiRequest['amount'] === $nullifiedAmount
                && $event->response == $transbankResponse;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($nullifiedAmount, $token) {
            return $action === 'Refunding transaction'
                && $token === $context['token']
                && 'webpayMall.refund' === $context['api_request']->serviceAction
                && $nullifiedAmount === $context['api_request']['amount'];
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($transbankResponse, $nullifiedAmount, $token) {
            return $action === 'Response received'
                && $token === $context['token']
                && 'webpayMall.refund' === $context['api_request']->serviceAction
                && $nullifiedAmount === $context['api_request']['amount']
                && $transbankResponse == $context['response'];
        })->once()->andReturnNull();

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode($transbankResponse, JSON_THROW_ON_ERROR)),
        ]));

        $response = $this->transbank->webpayMall()->refund($commerceCode, $token, $buyOrder, $nullifiedAmount);

        static::assertEquals($response->getNullifiedAmount(), $nullifiedAmount);
        static::assertTrue($response->isSuccessful());

        foreach ($transbankResponse as $key => $value) {
            static::assertEquals($value, $response->{'get' . static::snakeCaseToPascalCase($key)}());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('POST', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(Webpay::ENDPOINT_REFUND, $this->requests[0]['request'], [
            '{token}' => $token
        ]);
    }

    public function test_capture(): void
    {
        $token = '01abe7ccf59d4f9d099aa881a41c0bcebf4d3557c976a3694051652ff469c3e7';

        $commerceCode = '597055555531';
        $buyOrder = 'test_buyOrder';
        $authorizationCode = '12345';
        $captureAmount = 1000;

        $transbankResponse = [
            'token' => $token,
            'authorization_code' => $authorizationCode,
            'authorization_date' => '2019-03-20T20:18:20Z',
            'captured_amount' => $captureAmount,
            'response_code' => 0,
        ];

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCompleted $event) use ($captureAmount, $authorizationCode, $buyOrder, $transbankResponse) {
            return 'webpayMall.capture' === $event->apiRequest->serviceAction
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

        $response = $this->transbank->webpayMall()->capture($commerceCode, $token, $buyOrder, $authorizationCode, $captureAmount);

        static::assertEquals($response->getToken(), $token);
        static::assertEquals($response->getCapturedAmount(), $captureAmount);
        static::assertEquals($response->getAuthorizationCode(), $authorizationCode);
        static::assertTrue($response->isSuccessful());

        static::assertCount(1, $this->requests);
        static::assertEquals('PUT', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(WebpayMall::ENDPOINT_CAPTURE, $this->requests[0]['request'], [
            '{token}' => $token
        ]);
    }
}
