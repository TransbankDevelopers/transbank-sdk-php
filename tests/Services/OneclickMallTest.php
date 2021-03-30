<?php /** @noinspection JsonEncodingApiUsageInspection */

namespace Tests\Services;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\FormatsToCamelCase;
use Transbank\Sdk\ApiRequest;
use Transbank\Sdk\Credentials\Credentials;
use Transbank\Sdk\Events\TransactionCompleted;
use Transbank\Sdk\Events\TransactionCreating;
use Transbank\Sdk\Http\Connector;
use Transbank\Sdk\Services\OneclickMall;
use Transbank\Sdk\Services\Transactions\Detail;

class OneclickMallTest extends TestCase
{
    use FormatsToCamelCase;
    use AssertsApiEndpoint;
    use TestsServices;

    public function test_uses_production_credentials(): void
    {
        $this->transbank->connector = Mockery::mock(Connector::class);
        $this->transbank->connector->shouldReceive('send')->withArgs(
            function (string $method, string $endpoint, ApiRequest $apiRequest, Credentials $credentials) {
                static::assertEquals('test_key', $credentials->key);
                static::assertEquals('test_secret', $credentials->secret);
                return true;
            }
        )->times(6)->andReturn(['token' => 'test_token', 'url_webpay' => 'test_url']);

        $this->logger->shouldReceive('debug')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();
        $this->dispatcher->shouldReceive('dispatch')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();

        $this->transbank->toProduction(['oneclickMall' => ['key' => 'test_key', 'secret' => 'test_secret']]);

        $this->transbank->oneclickMall()->start('test_username', 'test_email', 'test_response_url');
        $this->transbank->oneclickMall()->finish('test_token');
        $this->transbank->oneclickMall()->authorize('test_user', 'test_username', 'test_buyorder', []);
        $this->transbank->oneclickMall()->status('test_buyorder');
        $this->transbank->oneclickMall()->refund('test_buyorder', 'test_commerce_code', 'test_child_order', 1000);
        $this->transbank->oneclickMall()->capture('test_commerce_code', 'test_buy_order', '1234', 1000);
    }

    public function test_uses_integration_credentials_by_default(): void
    {
        $this->transbank->connector = Mockery::mock(Connector::class);
        $this->transbank->connector->shouldReceive('send')->withArgs(
            function (string $method, string $endpoint, ApiRequest $apiRequest, Credentials $credentials) {
                if ($apiRequest->serviceAction === 'oneclickMall.capture') {
                    static::assertEquals(Credentials::INTEGRATION_KEYS['oneclickMall.capture'], $credentials->key);
                } else {
                    static::assertEquals(Credentials::INTEGRATION_KEYS['oneclickMall'], $credentials->key);
                }
                static::assertEquals(Credentials::INTEGRATION_SECRET, $credentials->secret);
                return true;
            }
        )->times(6)->andReturn(['token' => 'test_token', 'url_webpay' => 'test_url']);

        $this->logger->shouldReceive('debug')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();
        $this->dispatcher->shouldReceive('dispatch')->withAnyArgs()->zeroOrMoreTimes()->andReturnNull();

        $this->transbank->oneclickMall()->start('test_username', 'test_email', 'test_response_url');
        $this->transbank->oneclickMall()->finish('test_token');
        $this->transbank->oneclickMall()->authorize('test_user', 'test_username', 'test_buyorder', []);
        $this->transbank->oneclickMall()->status('test_buyorder');
        $this->transbank->oneclickMall()->refund('test_buyorder', 'test_commerce_code', 'test_child_order', 1000);
        $this->transbank->oneclickMall()->capture('test_commerce_code', 'test_buy_order', '1234', 1000);
    }

    public function test_start(): void
    {
        $username = 'test_username';
        $email = 'test_email';
        $responseUrl = 'responseUrl';

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode([
               'token' => $token = '01ab1cc073c91fe5fc08a1b3b00ac3f63033a0e3dbdfdb1fde55c044ed8161b6',
               'url_webpay' => $url = 'https://webpay3g.transbank.cl/webpayserver/initTransaction',
           ], JSON_THROW_ON_ERROR)),
        ]));

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use (
            $responseUrl,
            $email, $username) {
            static::assertEquals('Creating subscription', $action);
            static::assertEquals($username, $context['api_request']['username']);
            static::assertEquals($email, $context['api_request']['email']);
            static::assertEquals($responseUrl, $context['api_request']['response_url']);

            return true;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCreating $event) use (
            $responseUrl,
            $email,
            $username) {
                static::assertEquals('oneclickMall.start', $event->apiRequest->serviceAction);
                static::assertEquals($username, $event->apiRequest['username']);
                static::assertEquals($email, $event->apiRequest['email']);
                static::assertEquals($responseUrl, $event->apiRequest['response_url']);

                return true;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use (
            $url,
            $token,
            $responseUrl,
            $email, $username) {
                static::assertEquals('Response received', $action);
                static::assertEquals($username, $context['api_request']['username']);
                static::assertEquals($email, $context['api_request']['email']);
                static::assertEquals($responseUrl, $context['api_request']['response_url']);
                static::assertEquals($token, $context['response']['token']);
                static::assertEquals($url, $context['response']['url_webpay']);
            return true;
        })->once()->andReturnNull();

        $response = $this->transbank->oneclickMall()->start($username, $email, $responseUrl);

        static::assertEquals($response->getToken(), $token);
        static::assertEquals($response->getUrl(), $url);

        static::assertCount(1, $this->requests);
        static::assertEquals('POST', $this->requests[0]['request']->getMethod());

        static::assertEndpointPath(OneclickMall::ENDPOINT_START, $this->requests[0]['request']);
        static::assertRequestContents(
            [
                'username' => $username,
                'email' => $email,
                'response_url' => $responseUrl,
            ],
            $this->requests[0]['request']
        );
    }

    public function test_finish(): void
    {
        $token = '01ab1cc073c91fe5fc08a1b3b00ac3f63033a0e3dbdfdb1fde55c044ed8161b6';
        $responseCode = 0;
        $tbkuser = 'b6bd6ba3-e718-4107-9386-d2b099a8dd42';
        $authorizationCode = '123456';
        $cardType = 'Visa';
        $cardNumber = 'XXXXXXXXXXXX6623';

        $transbankResponse = [
            'response_code' => $responseCode,
            'tbk_user' => $tbkuser,
            'authorization_code' => $authorizationCode,
            'card_type' => $cardType,
            'card_number' => $cardNumber,
        ];

        $this->handlerStack->setHandler(
            new MockHandler(
                [new Response(200, ['content-type' => 'application/json',], json_encode($transbankResponse))]
            )
        );

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use ($token) {
            static::assertEquals('Finishing subscription', $action);
            static::assertEquals($token, $context['token']);
            static::assertEquals('oneclickMall.finish', $context['api_request']->serviceAction);

            return true;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use (
            $cardNumber,
            $cardType,
            $authorizationCode,
            $tbkuser,
            $responseCode,
            $token) {
            static::assertEquals('Response received', $action);
            static::assertEquals($token, $context['token']);
            static::assertEquals('oneclickMall.finish', $context['api_request']->serviceAction);
            static::assertEquals($responseCode, $context['response']['response_code']);
            static::assertEquals($tbkuser, $context['response']['tbk_user']);
            static::assertEquals($authorizationCode, $context['response']['authorization_code']);
            static::assertEquals($cardType, $context['response']['card_type']);
            static::assertEquals($cardNumber, $context['response']['card_number']);

            return true;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function(TransactionCompleted $event) use (
            $cardNumber,
            $cardType,
            $authorizationCode,
            $tbkuser,
            $responseCode
        ) {
            static::assertEquals('oneclickMall.finish', $event->apiRequest->serviceAction);
            static::assertEquals($responseCode, $event->response['response_code']);
            static::assertEquals($tbkuser, $event->response['tbk_user']);
            static::assertEquals($authorizationCode, $event->response['authorization_code']);
            static::assertEquals($cardType, $event->response['card_type']);
            static::assertEquals($cardNumber, $event->response['card_number']);

            return true;
        })->once()->andReturnNull();

        $response = $this->transbank->oneclickMall()->finish($token);

        static::assertEquals($responseCode, $response->getResponseCode());
        static::assertEquals($tbkuser, $response->getTbkUser());
        static::assertEquals($authorizationCode, $response->getAuthorizationCode());
        static::assertEquals($cardType, $response->getCardType());
        static::assertEquals($cardNumber, $response->getCardNumber());

        static::assertCount(1, $this->requests);
        static::assertEquals('PUT', $this->requests[0]['request']->getMethod());

        static::assertEndpointPath(OneclickMall::ENDPOINT_FINISH, $this->requests[0]['request'], [
            '{token}' => $token,
        ]);
        static::assertRequestContentsEmpty($this->requests[0]['request']);
    }

    public function test_delete(): void
    {
        $tbkUser = 'b6bd6ba3-e718-4107-9386-d2b099a8dd42';
        $username = 'test_username';

        $this->handlerStack->setHandler(new MockHandler([new Response(204, ['content-type' => 'application/json'])]));

        $this->logger->shouldReceive('debug')->withArgs(function(string $action, array $context) use (
            $username,
            $tbkUser) {
            static::assertEquals('Deleting subscription', $action);
            static::assertEquals($tbkUser, $context['api_request']['tbk_user']);
            static::assertEquals($username, $context['api_request']['username']);
            static::assertEquals('oneclickMall.delete', $context['api_request']->serviceAction);

            return true;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function(string $message, array $context) use (
            $username,
            $tbkUser) {
            static::assertEquals('Response received', $message);
            static::assertEquals($tbkUser, $context['api_request']['tbk_user']);
            static::assertEquals($username, $context['api_request']['username']);
            static::assertEquals('oneclickMall.delete', $context['api_request']->serviceAction);
            static::assertEmpty($context['response']);

            return true;
        })->once()->andReturnNull();

        $this->transbank->oneclickMall()->delete($tbkUser, $username);

        static::assertCount(1, $this->requests);
        static::assertEquals('DELETE', $this->requests[0]['request']->getMethod());

        static::assertEndpointPath(OneclickMall::ENDPOINT_DELETE, $this->requests[0]['request']);

        static::assertRequestContents(
            [
                'tbk_user' => $tbkUser,
                'username' => $username,
            ],
            $this->requests[0]['request']
        );
    }

    public function test_authorize(): void
    {
        $tbkUser = 'test_tbk_user';
        $username = 'test_username';
        $parentBuyOrder = 'test_parent_buy_order';
        $details = [
            [
                'commerce_code' => '597055555542',
                'buy_order' => 'ordenCompra123445',
                'amount' => 1000,
                'installments_number' => 5
            ]
        ];

        $transbankResponse = [
            'buy_order' => '415034240',
            'card_detail' => [
                'card_number' => '6623',
            ],
            'accounting_date' => '0321',
            'transaction_date' => '2019-03-21T15:43:48.523Z',
            'details' => [
                [
                    'amount' => 500,
                    'status' => 'AUTHORIZED',
                    'authorization_code' => '1213',
                    'payment_type_code' => 'VN',
                    'response_code' => 0,
                    'installments_number' => 0,
                    'commerce_code' => '597055555542',
                    'buy_order' => '505479072',
                ],
            ],
        ];

        $this->handlerStack->setHandler(
            new MockHandler(
                [new Response(200, ['content-type' => 'application/json',], json_encode($transbankResponse))]
            )
        );

        $this->logger->shouldReceive('debug')->withArgs(function (string $action, array $context) use (
            $details,
            $parentBuyOrder,
            $username,
            $tbkUser) {
            static::assertEquals('Authorizing transaction', $action);
            static::assertEquals($tbkUser, $context['api_request']['tbk_user']);
            static::assertEquals($username, $context['api_request']['username']);
            static::assertEquals($parentBuyOrder, $context['api_request']['buy_order']);
            static::assertEquals($details, $context['api_request']['details']);

            return true;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function (TransactionCreating $event) use (
            $details,
            $parentBuyOrder,
            $username,
            $tbkUser) {
            static::assertEquals($tbkUser, $event->apiRequest['tbk_user']);
            static::assertEquals($username, $event->apiRequest['username']);
            static::assertEquals($parentBuyOrder, $event->apiRequest['buy_order']);
            static::assertEquals($details, $event->apiRequest['details']);

            return true;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function (string $action, array $context) use (
            $transbankResponse,
            $details,
            $parentBuyOrder,
            $username,
            $tbkUser) {
            static::assertEquals('oneclickMall.authorize', $context['api_request']->serviceAction);

            static::assertEquals($tbkUser, $context['api_request']['tbk_user']);
            static::assertEquals($username, $context['api_request']['username']);
            static::assertEquals($parentBuyOrder, $context['api_request']['buy_order']);
            static::assertEquals($details, $context['api_request']['details']);
            static::assertEquals($transbankResponse, $context['response']);

            return true;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function (TransactionCompleted $event) use (
            $transbankResponse,
            $details,
            $parentBuyOrder,
            $username,
            $tbkUser) {
            static::assertEquals('oneclickMall.authorize', $event->apiRequest->serviceAction);

            static::assertEquals($tbkUser, $event->apiRequest['tbk_user']);
            static::assertEquals($username, $event->apiRequest['username']);
            static::assertEquals($parentBuyOrder, $event->apiRequest['buy_order']);
            static::assertEquals($details, $event->apiRequest['details']);

            static::assertEquals($transbankResponse, $event->response);

            return true;
        })->once()->andReturnNull();

        $response = $this->transbank->oneclickMall()->authorize($tbkUser, $username, $parentBuyOrder, $details);

        static::assertEquals($response->getBuyOrder(), $transbankResponse['buy_order']);
        static::assertEquals($response->getCardDetail(), $transbankResponse['card_detail']);
        static::assertEquals($response->getAccountingDate(), $transbankResponse['accounting_date']);
        static::assertEquals($response->getTransactionDate(), $transbankResponse['transaction_date']);

        foreach ($transbankResponse['details'] as $index => $detail) {
            static::assertInstanceOf(Detail::class, $response->details[$index]);

            static::assertEquals($detail['amount'], $response->details[$index]['amount']);
            static::assertEquals($detail['status'], $response->details[$index]['status']);
            static::assertEquals($detail['authorization_code'], $response->details[$index]['authorization_code']);
            static::assertEquals($detail['payment_type_code'], $response->details[$index]['payment_type_code']);
            static::assertEquals($detail['response_code'], $response->details[$index]['response_code']);
            static::assertEquals($detail['installments_number'], $response->details[$index]['installments_number']);
            static::assertEquals($detail['commerce_code'], $response->details[$index]['commerce_code']);
            static::assertEquals($detail['buy_order'], $response->details[$index]['buy_order']);
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('POST', $this->requests[0]['request']->getMethod());

        static::assertEndpointPath(OneclickMall::ENDPOINT_AUTHORIZE, $this->requests[0]['request']);
        static::assertRequestContents(
            [
                'tbk_user' => $tbkUser,
                'username' => $username,
                'buy_order' => $parentBuyOrder,
                'details' => $details,
            ],
            $this->requests[0]['request']
        );
    }

    public function test_status(): void
    {
        $buyOrder = 'test_buy_order';

        $transbankResponse = [
            'buy_order' => '415034240',
            'card_detail' => [
                'card_number' => '6623',
            ],
            'accounting_date' => '0321',
            'transaction_date' => '2019-03-21T15:43:48.523Z',
            'details' => [
                [
                    'amount' => 500,
                    'status' => 'AUTHORIZED',
                    'authorization_code' => '1213',
                    'payment_type_code' => 'VN',
                    'response_code' => 0,
                    'installments_number' => 0,
                    'commerce_code' => '597055555542',
                    'buy_order' => '505479072',
                ],
            ],
        ];

        $this->handlerStack->setHandler(
            new MockHandler(
                [new Response(200, ['content-type' => 'application/json'], json_encode($transbankResponse))]
            )
        );

        $this->dispatcher->shouldNotReceive('dispatch');

        $this->logger->shouldReceive('debug')->withArgs(function (string $action, array $context) use ($buyOrder) {
            static::assertEquals('Authorizing transaction', $action);
            static::assertEquals('oneclickMall.status', $context['api_request']->serviceAction);
            static::assertEquals($buyOrder, $context['buy_order']);

            return true;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(
            function (string $action, array $context) use ($transbankResponse) {
                static::assertEquals('Response received', $action);
                static::assertEquals('oneclickMall.status', $context['api_request']->serviceAction);

                static::assertEquals($transbankResponse, $context['response']);

                return true;
            }
        )->once()->andReturnNull();

        $response = $this->transbank->oneclickMall()->status($buyOrder);

        foreach ($transbankResponse as $key => $value) {
            if ($key !== 'details') {
                static::assertEquals($value, $response->{'get' . self::snakeCaseToPascalCase($key)}());
            }
        }

        foreach ($transbankResponse['details'] as $index => $detail) {
            static::assertInstanceOf(Detail::class, $response->details[$index]);

            foreach ($transbankResponse['details'][$index] as $key => $value) {
                static::assertEquals(
                    $value,
                    $response->details[$index]->{'get' . static::snakeCaseToPascalCase($key)}()
                );
            }
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('GET', $this->requests[0]['request']->getMethod());

        static::assertEndpointPath(OneclickMall::ENDPOINT_STATUS, $this->requests[0]['request'], [
            '{buyOrder}' => $buyOrder
        ]);

        static::assertRequestContentsEmpty($this->requests[0]['request']);
    }

    public function test_refund(): void
    {
        $buyOrder = 'test_buy_order';
        $childCommerceCode = 'test_commerce_code';
        $childBuyOrder = 'test_child_buy_order';
        $amount = 1000;

        $transbankResponse = [
            'type' => 'NULLIFIED',
            'authorization_code' => '123456',
            'authorization_date' => '2019-03-20T20:18:20Z',
            'nullified_amount' => 1000.00,
            'balance' => 0.00,
            'response_code' => 0,
        ];

        $this->handlerStack->setHandler(
            new MockHandler(
                [new Response(200, ['content-type' => 'application/json'], json_encode($transbankResponse))]
            )
        );

        $this->logger->shouldReceive('debug')->withArgs(function (string $action, array $context) use (
            $amount,
            $childBuyOrder,
            $childCommerceCode,
            $buyOrder) {
            static::assertEquals('Refunding transaction', $action);

            static::assertEquals($buyOrder, $context['buy_order']);

            static::assertEquals('oneclickMall.refund', $context['api_request']->serviceAction);
            static::assertEquals($childCommerceCode, $context['api_request']['commerce_code']);
            static::assertEquals($childBuyOrder, $context['api_request']['detail_buy_order']);
            static::assertEquals($amount, $context['api_request']['amount']);

            return true;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function (TransactionCreating $event) use (
            $amount,
            $childBuyOrder,
            $childCommerceCode
        ) {
            static::assertEquals('oneclickMall.refund', $event->apiRequest->serviceAction);
            static::assertEquals($childCommerceCode, $event->apiRequest['commerce_code']);
            static::assertEquals($childBuyOrder, $event->apiRequest['detail_buy_order']);
            static::assertEquals($amount, $event->apiRequest['amount']);

            return true;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function (string $action, array $context) use (
            $transbankResponse,
            $amount,
            $childBuyOrder,
            $childCommerceCode,
            $buyOrder) {
            static::assertEquals('Response received', $action);

            static::assertEquals($buyOrder, $context['buy_order']);

            static::assertEquals('oneclickMall.refund', $context['api_request']->serviceAction);
            static::assertEquals($childCommerceCode, $context['api_request']['commerce_code']);
            static::assertEquals($childBuyOrder, $context['api_request']['detail_buy_order']);
            static::assertEquals($amount, $context['api_request']['amount']);

            static::assertEquals($transbankResponse, $context['response']);

            return true;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function (TransactionCompleted $event) use (
            $transbankResponse,
            $amount,
            $childBuyOrder,
            $childCommerceCode
        ) {
            static::assertEquals('oneclickMall.refund', $event->apiRequest->serviceAction);
            static::assertEquals($childCommerceCode, $event->apiRequest['commerce_code']);
            static::assertEquals($childBuyOrder, $event->apiRequest['detail_buy_order']);
            static::assertEquals($amount, $event->apiRequest['amount']);

            static::assertEquals($transbankResponse, $event->response);

            return true;
        })->once()->andReturnNull();

        $response = $this->transbank->oneclickMall()->refund($buyOrder, $childCommerceCode, $childBuyOrder, $amount);

        foreach ($transbankResponse as $key => $value) {
            static::assertEquals($value, $response->{'get' . static::snakeCaseToPascalCase($key)}());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('POST', $this->requests[0]['request']->getMethod());

        static::assertEndpointPath(OneclickMall::ENDPOINT_REFUND, $this->requests[0]['request'], [
            '{buyOrder}' => $buyOrder
        ]);

        static::assertRequestContents(
            [
                'commerce_code' => $childCommerceCode,
                'detail_buy_order' => $childBuyOrder,
                'amount' => $amount,
            ],
            $this->requests[0]['request']
        );
    }

    public function test_capture(): void
    {
        $commerceCode = 'test_commerce_code';
        $buyOrder = 'test_buy_order';
        $authorizationCode = 'test_authorization_code';
        $captureAmount = 1000;

        $transbankResponse = [
            'authorization_code' => '152759',
            'authorization_date' => '2020-04-03T01:49:50.181Z',
            'captured_amount' => 50,
            'response_code' => 0,
        ];

        $this->handlerStack->setHandler(
            new MockHandler(
                [new Response(200, ['content-type' => 'application/json'], json_encode($transbankResponse))]
            )
        );

        $this->logger->shouldReceive('debug')->withArgs(function (string $action, array $context) use (
            $captureAmount,
            $authorizationCode,
            $buyOrder,
            $commerceCode) {
            static::assertEquals('Capturing transaction', $action);

            static::assertEquals('oneclickMall.capture', $context['api_request']->serviceAction);

            static::assertEquals($commerceCode, $context['api_request']['commerce_code']);
            static::assertEquals($buyOrder, $context['api_request']['buy_order']);
            static::assertEquals($authorizationCode, $context['api_request']['authorization_code']);
            static::assertEquals($captureAmount, $context['api_request']['capture_amount']);

            return true;
        })->once()->andReturnNull();

        $this->logger->shouldReceive('debug')->withArgs(function (string $action, array $context) use (
            $transbankResponse,
            $captureAmount,
            $authorizationCode,
            $buyOrder,
            $commerceCode) {
            static::assertEquals('Response received', $action);

            static::assertEquals('oneclickMall.capture', $context['api_request']->serviceAction);

            static::assertEquals($commerceCode, $context['api_request']['commerce_code']);
            static::assertEquals($buyOrder, $context['api_request']['buy_order']);
            static::assertEquals($authorizationCode, $context['api_request']['authorization_code']);
            static::assertEquals($captureAmount, $context['api_request']['capture_amount']);

            static::assertEquals($transbankResponse, $context['response']);

            return true;
        })->once()->andReturnNull();

        $this->dispatcher->shouldReceive('dispatch')->withArgs(function (TransactionCompleted $event) use (
            $captureAmount,
            $authorizationCode,
            $buyOrder,
            $commerceCode,
            $transbankResponse
        ) {
            static::assertEquals('oneclickMall.capture', $event->apiRequest->serviceAction);

            static::assertEquals($commerceCode, $event->apiRequest['commerce_code']);
            static::assertEquals($buyOrder, $event->apiRequest['buy_order']);
            static::assertEquals($authorizationCode, $event->apiRequest['authorization_code']);
            static::assertEquals($captureAmount, $event->apiRequest['capture_amount']);


            static::assertEquals($transbankResponse, $event->response);

            return true;
        })->once()->andReturnNull();

        $response = $this->transbank->oneclickMall()->capture(
            $commerceCode,
            $buyOrder,
            $authorizationCode,
            $captureAmount
        );

        foreach ($transbankResponse as $key => $value) {
            static::assertEquals($value, $response->{'get' . self::snakeCaseToPascalCase($key)}());
        }

        static::assertCount(1, $this->requests);
        static::assertEquals('PUT', $this->requests[0]['request']->getMethod());

        static::assertEndpointPath(OneclickMall::ENDPOINT_CAPTURE, $this->requests[0]['request']);

        static::assertRequestContents(
            [
                'commerce_code' => $commerceCode,
                'buy_order' => $buyOrder,
                'authorization_code' => $authorizationCode,
                'capture_amount' => $captureAmount,
            ],
            $this->requests[0]['request']
        );
    }
}
