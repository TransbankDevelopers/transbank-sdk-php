<?php

namespace webpay_rest\OneClick;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Webpay\Options;
use Transbank\Webpay\Oneclick;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionAuthorizeException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\Oneclick\Exceptions\MallRefundTransactionException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionFinishException;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionDeleteException;
use Transbank\Webpay\Oneclick\MallInscription;
use Transbank\Webpay\Oneclick\MallTransaction;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionStartResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionAuthorizeResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\Oneclick\Responses\MallTransactionRefundResponse;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;

class TransbankOneclickTest extends TestCase
{
    public string $username;
    public string $email;
    public string $responseUrl;

    protected function setUp(): void
    {
        $this->createDemoData();
    }

    #[Test]
    public function it_creates_an_inscription()
    {
        $response = MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_API_KEY,
            Oneclick::INTEGRATION_COMMERCE_CODE
        )->start($this->username, $this->email, $this->responseUrl);

        $this->assertInstanceOf(InscriptionStartResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
        $this->assertNotEmpty($response->getUrlWebpay());
        $this->assertStringContainsString($response->getToken(), $response->getRedirectUrl());
        $this->assertStringContainsString($response->getUrlWebpay(), $response->getRedirectUrl());
    }

    #[Test]
    public function it_fails_creating_an_inscription_with_invalid_email()
    {
        $this->expectException(InscriptionStartException::class);
        $this->expectExceptionMessage('Invalid value for parameter: email');

        MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_API_KEY,
            Oneclick::INTEGRATION_COMMERCE_CODE
        )->start($this->username, 'not_an_email', $this->responseUrl);
    }

    #[Test]
    public function it_fails_creating_an_inscription_with_invalid_data()
    {
        $this->expectException(InscriptionStartException::class);
        $this->expectExceptionMessage('username is required');

        MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_API_KEY,
            Oneclick::INTEGRATION_COMMERCE_CODE
        )->start('', $this->email, $this->responseUrl);
    }

    #[Test]
    public function it_fails_trying_to_finish_inscription()
    {
        $this->createDemoData();

        $startResponse = MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_API_KEY,
            Oneclick::INTEGRATION_COMMERCE_CODE
        )->start($this->username, $this->email, $this->responseUrl);
        $this->assertInstanceOf(InscriptionStartResponse::class, $startResponse);
        $tokenResponse = $startResponse->getToken();
        $response = MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_API_KEY,
            Oneclick::INTEGRATION_COMMERCE_CODE
        )->finish($tokenResponse);
        $this->assertInstanceOf(InscriptionFinishResponse::class, $response);

        // -96 means the inscription has not finished yet.
        $this->assertEquals(-96, $response->getResponseCode());
    }

    #[Test]
    public function it_fails_authorizing_a_transaction_with_a_fake_token()
    {
        $mallTransaction = MallTransaction::buildForIntegration(
            Oneclick::INTEGRATION_API_KEY,
            Oneclick::INTEGRATION_COMMERCE_CODE
        );
        $exception = null;
        try {
            $mallTransaction->authorize($this->username, 'fakeToken', 'buyOrder2132312', [
                [
                    'commerce_code'       => Oneclick::INTEGRATION_CHILD_COMMERCE_CODE_1,
                    'buy_order'           => 'buyOrder122412',
                    'amount'              => 1000,
                    'installments_number' => 1,
                ],
            ]);
        } catch (MallTransactionAuthorizeException $e) {
            $exception = $e;
        }
        $this->assertInstanceOf(MallTransactionAuthorizeException::class, $exception);
        $lastResponse = $mallTransaction->getRequestService()->getLastResponse();
        $lastRequest = $mallTransaction->getRequestService()->getLastRequest();
        $this->assertNotNull($lastResponse);
        $this->assertEquals($exception->getFailedRequest(), $lastRequest);
        $this->assertEquals(500, $lastResponse->getStatusCode());

        $this->assertEqualsCanonicalizing([
            'username'  => $this->username,
            'tbk_user'  => 'fakeToken',
            'buy_order' => 'buyOrder2132312',
            'details'   => [
                [
                    'commerce_code' => Oneclick::INTEGRATION_CHILD_COMMERCE_CODE_1,
                    'amount'        => 1000,
                    'buy_order'     => 'buyOrder122412',
                    'installments_number' => 1
                ],
            ],
        ], $lastRequest->getPayload());
    }

    #[Test]
    public function it_fails_authorizing_a_transaction_with_no_username()
    {
        $this->expectException(MallTransactionAuthorizeException::class);
        MallTransaction::buildForIntegration(
            Oneclick::INTEGRATION_API_KEY,
            Oneclick::INTEGRATION_COMMERCE_CODE
        )->authorize('', 'fakeToken', 'buyOrder2132312', [
            [
                'commerce_code'       => Oneclick::INTEGRATION_CHILD_COMMERCE_CODE_1,
                'buy_order'           => 'buyOrder122412',
                'amount'              => 1000,
                'installments_number' => 1,
            ],
        ]);
    }

    protected function createDemoData()
    {
        $this->username = 'demo_' . rand(100000, 999999);
        $this->email = 'demo_' . rand(100000, 999999) . '@demo.cl';
        $this->responseUrl = 'http://demo.cl/return';
    }

    #[Test]
    public function it_configures_inscription_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $inscription = MallInscription::buildForIntegration($apiKey, $commerceCode);
        $inscriptionOptions = $inscription->getOptions();

        $this->assertSame($commerceCode, $inscriptionOptions->getCommerceCode());
        $this->assertSame($apiKey, $inscriptionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $inscriptionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_INTEGRATION, $inscriptionOptions->getApiBaseUrl());
    }

    #[Test]
    public function it_configures_inscription_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $inscription = MallInscription::buildForProduction($apiKey, $commerceCode);
        $inscriptionOptions = $inscription->getOptions();

        $this->assertSame($commerceCode, $inscriptionOptions->getCommerceCode());
        $this->assertSame($apiKey, $inscriptionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $inscriptionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $inscriptionOptions->getApiBaseUrl());
    }

    #[Test]
    public function it_configures_inscription_with_options()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $options = new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION);
        $inscription = new MallInscription($options);
        $inscriptionOptions = $inscription->getOptions();

        $this->assertSame($commerceCode, $inscriptionOptions->getCommerceCode());
        $this->assertSame($apiKey, $inscriptionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $inscriptionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $inscriptionOptions->getApiBaseUrl());
    }

    #[Test]
    public function it_configures_transaction_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = MallTransaction::buildForIntegration($apiKey, $commerceCode);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_INTEGRATION, $transactionOptions->getApiBaseUrl());
    }

    #[Test]
    public function it_configures_transaction_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = MallTransaction::buildForProduction($apiKey, $commerceCode);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $transactionOptions->getApiBaseUrl());
    }

    #[Test]
    public function it_configures_transaction_with_options()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $options = new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION);
        $transaction = new MallTransaction($options);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $transactionOptions->getApiBaseUrl());
    }

    #[Test]
    public function it_deletes_an_existing_inscription()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([]);
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION);
        $inscription = new MallInscription($options, $requestServiceMock);
        $deleteResponse = $inscription->delete('tbkTestUser', 'useNameTest');

        $this->assertTrue($deleteResponse);
    }

    #[Test]
    public function it_deletes_an_unexisting_inscription()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new WebpayRequestException("Could not obtain a response from Transbank API", null, 404));
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION);
        $inscription = new MallInscription($options, $requestServiceMock);
        $this->expectException(InscriptionDeleteException::class);
        $inscription->delete('tbkTestUser', 'useNameTest');
    }

    #[Test]
    public function it_returns_an_authorize_response()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                "buy_order" => "415034240",
                "card_detail" =>
                ["card_number" => "6623"],
                "accounting_date" => "0321",
                "transaction_date" => "2019-03-21T15:43:48.523Z",
                "details" => [
                    [
                        "amount" => 500,
                        "status" => "AUTHORIZED",
                        "authorization_code" => "1213",
                        "payment_type_code" => "VN",
                        "response_code" => 0,
                        "installments_number" => 0,
                        "commerce_code" => "597055555542",
                        "buy_order" => "505479072"
                    ]
                ]
            ]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $authorize = $mallTransaction->authorize($this->username, 'fakeToken', 'buyOrder2132312', [
            [
                'commerce_code'       => Oneclick::INTEGRATION_CHILD_COMMERCE_CODE_1,
                'buy_order'           => 'buyOrder122412',
                'amount'              => 1000,
                'installments_number' => 1,
            ],
        ]);

        $this->assertInstanceOf(MallTransactionAuthorizeResponse::class, $authorize);
    }

    #[Test]
    public function it_returns_an_capture_response()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                "authorization_code" => "authCode",
                "response_code" => 0,
                "captured_amount" => 10000,
                "authorization_date" => "2019-03-21T15:43:48.523Z"
            ]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $capture = $mallTransaction->capture('commerceChild', 'buyOrdChild', 'authCode', 10000);

        $this->assertInstanceOf(MallTransactionCaptureResponse::class, $capture);
    }

    #[Test]
    public function it_throws_a_capture_exception()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new WebpayRequestException('error', null, 404));
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $this->expectException(MallTransactionCaptureException::class);
        $mallTransaction->capture('commerceChild', 'buyOrdChild', 'authCode', 10000);
    }
    #[Test]
    public function it_returns_an_status_response()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                "buy_order" => "415034240",
                "card_detail" =>
                ["card_number" => "6623"],
                "accounting_date" => "0321",
                "transaction_date" => "2019-03-21T15:43:48.523Z",
                "details" => [

                    [
                        "amount" => 500,
                        "status" => "AUTHORIZED",
                        "authorization_code" => "1213",
                        "payment_type_code" => "VN",
                        "response_code" => 0,
                        "installments_number" => 0,
                        "commerce_code" => "597055555542",
                        "buy_order" => "505479072"
                    ]
                ]
            ]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $status = $mallTransaction->status('buyOrd');

        $this->assertInstanceOf(MallTransactionStatusResponse::class, $status);
    }

    #[Test]
    public function it_throws_a_status_exception()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new WebpayRequestException('error', null, 404));
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $this->expectException(MallTransactionStatusException::class);
        $mallTransaction->status('buyOrd');
    }
    #[Test]
    public function it_returns_an_refund_response()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                "type" => "REVERSED"
            ]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $refund = $mallTransaction->refund('buyOrd', 'childCommerce', 'childBuy', 12000);

        $this->assertInstanceOf(MallTransactionRefundResponse::class, $refund);
    }

    #[Test]
    public function it_throws_a_refund_exception()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new WebpayRequestException('error', null, 404));
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $this->expectException(MallRefundTransactionException::class);
        $mallTransaction->refund('buyOrd', 'childCommerce', 'childBuy', 12000);
    }

    #[Test]
    public function it_throws_a_finish_exception()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new WebpayRequestException('error', null, 404));
        $inscription = new MallInscription(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $this->expectException(InscriptionFinishException::class);
        $inscription->finish('fakeToken');
    }

    #[Test]
    public function it_throws_a_delete_exception()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new WebpayRequestException('error', null, 204));
        $inscription = new MallInscription(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $this->expectException(InscriptionDeleteException::class);
        $inscription->delete('tbkUser', 'userName');
    }

    #[Test]
    public function it_can_get_data_from_status_response()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                "buy_order" => "415034240",
                "card_detail" =>
                ["card_number" => "6623"],
                "accounting_date" => "0321",
                "transaction_date" => "2019-03-21T15:43:48.523Z",
                "details" => [

                    [
                        "amount" => 500,
                        "status" => "AUTHORIZED",
                        "authorization_code" => "1213",
                        "payment_type_code" => "VN",
                        "response_code" => 0,
                        "installments_number" => 0,
                        "commerce_code" => "597055555542",
                        "buy_order" => "505479072"
                    ]
                ]
            ]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $status = $mallTransaction->status('buyOrd');

        $this->assertEquals('2019-03-21T15:43:48.523Z', $status->getTransactionDate());
        $this->assertEquals('6623', $status->getCardNumber());
        $this->assertEquals('0321', $status->getAccountingDate());
        $this->assertIsArray($status->getDetails());
        $this->assertEquals('415034240', $status->getBuyOrder());
    }

    #[Test]
    public function it_can_check_is_approved()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn(["details" => [

                [
                    "amount" => 500,
                    "status" => "AUTHORIZED",
                    "authorization_code" => "1213",
                    "payment_type_code" => "VN",
                    "response_code" => 0,
                    "installments_number" => 0,
                    "commerce_code" => "597055555542",
                    "buy_order" => "505479072"
                ]
            ]]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $status = $mallTransaction->status('buyOrd');
        $this->assertTrue($status->isApproved());

        $status->details[0]->responseCode = -1;
        $this->assertFalse($status->isApproved());

        $status->details = [];
        $this->assertFalse($status->isApproved());
    }

    #[Test]
    public function it_can_get_data_from_capture_response()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                "authorization_code" => "authCode2",
                "response_code" => 0,
                "captured_amount" => 9900,
                "authorization_date" => "2019-04-21T15:43:48.523Z"
            ]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $capture = $mallTransaction->capture('commerceChild', 'buyOrdChild', 'authCode', 9900);

        $this->assertEquals('2019-04-21T15:43:48.523Z', $capture->getAuthorizationDate());
        $this->assertEquals(9900, $capture->getCapturedAmount());
        $this->assertEquals(0, $capture->getResponseCode());
    }

    #[Test]
    public function it_can_get_data_from_refund_response()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                "type" => "NULLIFIED",
                "authorization_code" => "123456",
                "authorization_date" => "2019-03-20T20:18:20Z",
                "nullified_amount" => 1000,
                "balance" => 0,
                "response_code" => 0
            ]);
        $mallTransaction = new MallTransaction(new Options('apiKey', 'commerce', Options::ENVIRONMENT_INTEGRATION), $requestServiceMock);
        $refund = $mallTransaction->refund('buyOrd', 'childCommerce', 'childBuy', 12000);

        $this->assertEquals('NULLIFIED', $refund->getType());
        $this->assertEquals('123456', $refund->getAuthorizationCode());
        $this->assertEquals('2019-03-20T20:18:20Z', $refund->getAuthorizationDate());
        $this->assertEquals(1000, $refund->getNullifiedAmount());
        $this->assertEquals(0, $refund->getBalance());
        $this->assertEquals(0, $refund->getResponseCode());
    }
}
