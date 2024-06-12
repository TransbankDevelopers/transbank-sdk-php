<?php

namespace webpay_rest\OneClick;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Options;
use Transbank\Webpay\Oneclick;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionAuthorizeException;
use Transbank\Webpay\Oneclick\MallInscription;
use Transbank\Webpay\Oneclick\MallTransaction;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionStartResponse;

class TransbankOneclickTest extends TestCase
{
    use ArraySubsetAsserts;
    public $username;
    public $email;
    public $responseUrl;

    protected function setUp(): void
    {
        $this->createDemoData();
    }

    /** @test */
    public function it_creates_an_inscription()
    {
        $response = MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_COMMERCE_CODE,
            Oneclick::INTEGRATION_API_KEY
        )->start($this->username, $this->email, $this->responseUrl);

        $this->assertInstanceOf(InscriptionStartResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
        $this->assertNotEmpty($response->getUrlWebpay());
        $this->assertStringContainsString($response->getToken(), $response->getRedirectUrl());
        $this->assertStringContainsString($response->getUrlWebpay(), $response->getRedirectUrl());
    }

    /** @test */
    public function it_fails_creating_an_inscription_with_invalid_email()
    {
        $this->expectException(InscriptionStartException::class);
        $this->expectExceptionMessage('Invalid value for parameter: email');

        MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_COMMERCE_CODE,
            Oneclick::INTEGRATION_API_KEY
        )->start($this->username, 'not_an_email', $this->responseUrl);
    }

    /** @test */
    public function it_fails_creating_an_inscription_with_invalid_data()
    {
        $this->expectException(InscriptionStartException::class);
        $this->expectExceptionMessage('username is required');

        MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_COMMERCE_CODE,
            Oneclick::INTEGRATION_API_KEY
        )->start('', $this->email, $this->responseUrl);
    }

    /** @test */
    public function it_fails_trying_to_finish_inscription()
    {
        $this->createDemoData();
        $startResponse = MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_COMMERCE_CODE,
            Oneclick::INTEGRATION_API_KEY
        )->start($this->username, $this->email, $this->responseUrl);

        $response = MallInscription::buildForIntegration(
            Oneclick::INTEGRATION_COMMERCE_CODE,
            Oneclick::INTEGRATION_API_KEY
        )->finish($startResponse->getToken());
        $this->assertInstanceOf(InscriptionFinishResponse::class, $response);

        // -96 means the inscription has not finished yet.
        $this->assertEquals(-96, $response->getResponseCode());
    }

    /** @test */
    public function it_fails_authorizing_a_transaction_with_a_fake_token()
    {
        $mallTransaction = MallTransaction::buildForIntegration(
            Oneclick::INTEGRATION_COMMERCE_CODE,
            Oneclick::INTEGRATION_API_KEY
        );

        try {
            $mallTransaction->authorize($this->username, 'fakeToken', 'buyOrder2132312', [
                [
                    'commerce_code'       => Oneclick::INTEGRATION_CHILD_COMMERCE_CODE_1,
                    'buy_order'           => 'buyOrder122412',
                    'amount'              => 1000,
                    'installments_number' => 1,
                ],
            ]);
            $this->assertTrue(false, 'Should not be executed this line');
        } catch (MallTransactionAuthorizeException $exception) {
            $lastResponse = $mallTransaction->getRequestService()->getLastResponse();
            $lastRequest = $mallTransaction->getRequestService()->getLastRequest();
            $this->assertNotNull($lastResponse);
            $this->assertEquals($exception->getFailedRequest(), $lastRequest);
            $this->assertEquals(500, $lastResponse->getStatusCode());

            $this->assertArraySubset([
                'username'  => $this->username,
                'tbk_user'  => 'fakeToken',
                'buy_order' => 'buyOrder2132312',
                'details'   => [
                    [
                        'commerce_code' => Oneclick::INTEGRATION_CHILD_COMMERCE_CODE_1,
                        'amount'        => 1000,
                        'buy_order'     => 'buyOrder122412',
                    ],
                ],
            ], $lastRequest->getPayload());
        }
    }

    /** @test */
    public function it_fails_authorizing_a_transaction_with_no_username()
    {
        $this->expectException(MallTransactionAuthorizeException::class, 'username is required');
        MallTransaction::buildForIntegration(
            Oneclick::INTEGRATION_COMMERCE_CODE,
            Oneclick::INTEGRATION_API_KEY
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

    /** @test */
    public function it_configures_inscription_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $inscription = MallInscription::buildForIntegration($commerceCode, $apiKey);
        $inscriptionOptions = $inscription->getOptions();

        $this->assertSame($commerceCode, $inscriptionOptions->getCommerceCode());
        $this->assertSame($apiKey, $inscriptionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $inscriptionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_INTEGRATION, $inscriptionOptions->getApiBaseUrl());
    }

    /** @test */
    public function it_configures_inscription_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $inscription = MallInscription::buildForProduction($commerceCode, $apiKey);
        $inscriptionOptions = $inscription->getOptions();

        $this->assertSame($commerceCode, $inscriptionOptions->getCommerceCode());
        $this->assertSame($apiKey, $inscriptionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $inscriptionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $inscriptionOptions->getApiBaseUrl());
    }

    /** @test */
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

    /** @test */
    public function it_configures_transaction_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = MallTransaction::buildForIntegration($commerceCode, $apiKey);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_INTEGRATION, $transactionOptions->getApiBaseUrl());
    }

    /** @test */
    public function it_configures_transaction_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = MallTransaction::buildForProduction($commerceCode, $apiKey);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $transactionOptions->getApiBaseUrl());
    }

        /** @test */
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
}
