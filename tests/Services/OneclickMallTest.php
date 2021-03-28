<?php

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
        )->times(6)->andReturn(['token' => 'test_token', 'url' => 'test_url']);

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
        )->times(6)->andReturn(['token' => 'test_token', 'url' => 'test_url']);

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
               'url' => $url = 'https://webpay3g.transbank.cl/webpayserver/initTransaction',
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
                static::assertEquals($url, $context['response']['url']);
            return true;
        })->once()->andReturnNull();

        $response = $this->transbank->oneclickMall()->start($username, $email, $responseUrl);

        static::assertEquals($response->getToken(), $token);
        static::assertEquals($response->getUrl(), $url);

        static::assertCount(1, $this->requests);
        static::assertEquals('POST', $this->requests[0]['request']->getMethod());

        static::assertApiEndpoint(OneclickMall::ENDPOINT_START, $this->requests[0]['request']);
    }

    public function test_finish()
    {
        $token = '01ab1cc073c91fe5fc08a1b3b00ac3f63033a0e3dbdfdb1fde55c044ed8161b6';
        $responseCode = 0;
        $tbkuser = 'b6bd6ba3-e718-4107-9386-d2b099a8dd42';
        $authorizationCode = '123456';
        $cardType = 'Visa';
        $cardNumber = 'XXXXXXXXXXXX6623';

        $this->handlerStack->setHandler(new MockHandler([
            new Response(200, [
                'content-type' => 'application/json',
            ], json_encode([
               'response_code' => $responseCode,
               'tbk_user' => $tbkuser,
               'authorization_code' => $authorizationCode,
               'card_type' => $cardType,
               'card_number' => $cardNumber,
            ], JSON_THROW_ON_ERROR)),
        ]));

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

        static::assertApiEndpoint(OneclickMall::ENDPOINT_FINISH, $this->requests[0]['request'], [
            '{token}' => $token,
        ]);
    }
}
