<?php

namespace webpay_rest\OneClick;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassComercio\Inscription;
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
        $response = MallInscription::build()->start($this->username, $this->email, $this->responseUrl);
        $this->assertInstanceOf(InscriptionStartResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
        $this->assertNotEmpty($response->getUrlWebpay());
        $this->assertStringContainsString($response->getToken(), $response->getRedirectUrl());
        $this->assertStringContainsString($response->getUrlWebpay(), $response->getRedirectUrl());
    }

    /** @test */
    public function it_fails_creating_an_inscription_with_invalid_email()
    {
        $this->expectException(InscriptionStartException::class, 'Invalid value for parameter: email');
        $response = MallInscription::build()->start($this->username, 'not_an_email', $this->responseUrl);
    }

    /** @test */
    public function it_fails_creating_an_inscription_with_invalid_data()
    {
        $this->expectException(InscriptionStartException::class, 'username is required');
        $response = MallInscription::build()->start('', $this->email, $this->responseUrl);
    }

    /** @test */
    public function it_fails_trying_to_finish_inscription()
    {
        $this->createDemoData();
        $startResponse = MallInscription::build()->start($this->username, $this->email, $this->responseUrl);
        $response = MallInscription::build()->finish($startResponse->getToken());
        $this->assertInstanceOf(InscriptionFinishResponse::class, $response);

        // -96 means the inscription has not finished yet.
        $this->assertEquals(-96, $response->getResponseCode());
    }

    /** @test */
    public function it_fails_authorizing_a_transaction_with_a_fake_token()
    {
        $mallTransaction = new MallTransaction();

        try {
            $response = $mallTransaction->authorize($this->username, 'fakeToken', 'buyOrder2132312', [
                [
                    'commerce_code'       => MallInscription::DEFAULT_CHILD_COMMERCE_CODE_1,
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
                        'commerce_code' => MallInscription::DEFAULT_CHILD_COMMERCE_CODE_1,
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
        $response = MallTransaction::build()->authorize('', 'fakeToken', 'buyOrder2132312', [
            [
                'commerce_code'       => Oneclick::DEFAULT_CHILD_COMMERCE_CODE_1,
                'buy_order'           => 'buyOrder122412',
                'amount'              => 1000,
                'installments_number' => 1,
            ],
        ]);
    }

    protected function createDemoData()
    {
        $this->username = 'demo_'.rand(100000, 999999);
        $this->email = 'demo_'.rand(100000, 999999).'@demo.cl';
        $this->responseUrl = 'http://demo.cl/return';
    }
}
