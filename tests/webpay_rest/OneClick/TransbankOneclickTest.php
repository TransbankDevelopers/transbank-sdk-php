<?php

namespace webpay_rest\OneClick;

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Oneclick\Exceptions\InscriptionStartException;
use Transbank\Webpay\Oneclick\Exceptions\MallTransactionAuthorizeException;
use Transbank\Webpay\Oneclick\MallInscription;
use Transbank\Webpay\Oneclick\MallTransaction;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Webpay\Oneclick\Responses\InscriptionStartResponse;
use Transbank\Webpay\Options;

class TransbankOneclickTest extends TestCase
{
    public $username;
    public $email;

    public function setUp(): void
    {
        $this->createDemoData();
    }

    /** @test */
    public function it_creates_an_inscription()
    {
        $response = MallInscription::start($this->username, $this->email, 'http://demo.cl/return');
        $this->assertInstanceOf(InscriptionStartResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
        $this->assertNotEmpty($response->getUrlWebpay());
        $this->assertContains($response->getToken(), $response->getRedirectUrl());
        $this->assertContains($response->getUrlWebpay(), $response->getRedirectUrl());
    }

    /** @test */
    public function it_fails_creating_an_inscription_with_invalid_email()
    {
        $this->setExpectedException(InscriptionStartException::class, 'Invalid value for parameter: email');
        $response = MallInscription::start($this->username, 'not_an_email', 'http://demo.cl/return');
    }

    /** @test */
    public function it_fails_creating_an_inscription_with_invalid_data()
    {
        $this->setExpectedException(InscriptionStartException::class, 'username is required');
        $response = MallInscription::start('', $this->email, 'http://demo.cl/return');
    }

    /** @test */
    public function it_fails_trying_to_finish_inscription()
    {
        $this->createDemoData();
        $startResponse = MallInscription::start($this->username, $this->email, 'http://demo.cl/return');
        $response = MallInscription::finish($startResponse->getToken());
        $this->assertInstanceOf(InscriptionFinishResponse::class, $response);

        // -96 means the inscription has not finished yet.
        $this->assertEquals(-96, $response->getResponseCode());
    }

    /** @test */
    public function it_fails_authorizing_a_transaction_with_a_fake_token()
    {
        try {
            $response = MallTransaction::authorize($this->username, 'fakeToken', 'buyOrder2132312', [
                [
                    'commerce_code'       => Options::DEFAULT_ONECLICK_MALL_CHILD_COMMERCE_CODE_1,
                    'buy_order'           => 'buyOrder122412',
                    'amount'              => 1000,
                    'installments_number' => 1,
                ],
            ]);
            $this->assertFalse(true, 'Should not be executed this line');
        } catch (MallTransactionAuthorizeException $exception) {
            $lastResponse = MallTransaction::getLastResponse();
            $lastRequest = MallTransaction::getLastRequest();
            $this->assertNotNull($lastResponse);
            $this->assertEquals($exception->getFailedRequest(), $lastRequest);
            $this->assertEquals(500, $lastResponse->getStatusCode());
            $this->assertArraySubset([
                'username'  => $this->username,
                'tbk_user'  => 'fakeToken',
                'buy_order' => 'buyOrder2132312',
                'details'   => [
                    [
                        'commerce_code' => Options::DEFAULT_ONECLICK_MALL_CHILD_COMMERCE_CODE_1,
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
        $this->setExpectedException(MallTransactionAuthorizeException::class, 'username is required');
        $response = MallTransaction::authorize('', 'fakeToken', 'buyOrder2132312', [
            [
                'commerce_code'       => Options::DEFAULT_ONECLICK_MALL_CHILD_COMMERCE_CODE_1,
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
    }
}
