<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;

class OneClickInscriptionFinishResponseTest extends TestCase
{

    protected array $json;
    protected InscriptionFinishResponse $inscriptionResponse;

    protected function setUp(): void
    {
        $this->json = [
            'response_code' => 0,
            'tbk_user' => '123456',
            'authorization_code' => '7890',
            'card_type' => 'Visa',
            'card_number' => '**** **** **** 1234'
        ];
        $this->inscriptionResponse = new InscriptionFinishResponse($this->json);
    }
    #[Test]
    public function it_can_be_initialized_from_json()
    {
        $response = new InscriptionFinishResponse($this->json);

        $this->assertSame(0, $response->responseCode);
        $this->assertSame('123456', $response->tbkUser);
        $this->assertSame('7890', $response->authorizationCode);
        $this->assertSame('Visa', $response->cardType);
        $this->assertSame('**** **** **** 1234', $response->cardNumber);
    }

    #[Test]
    public function it_returns_true_when_response_code_is_approved()
    {
        $this->assertTrue($this->inscriptionResponse->isApproved());
    }

    #[Test]
    public function it_returns_false_when_response_code_is_not_approved()
    {
        $json = $this->json;
        $json['response_code'] = 1;
        $response = new InscriptionFinishResponse($json);
        $this->assertFalse($response->isApproved());
    }

    #[Test]
    public function it_can_get_response_code()
    {
        $this->assertSame(0, $this->inscriptionResponse->getResponseCode());
    }

    #[Test]
    public function it_can_get_tbk_user()
    {
        $this->assertSame('123456', $this->inscriptionResponse->getTbkUser());
    }

    #[Test]
    public function it_can_get_authorization_code()
    {
        $this->assertSame('7890', $this->inscriptionResponse->getAuthorizationCode());
    }

    #[Test]
    public function it_can_get_card_type()
    {
        $this->assertSame('Visa', $this->inscriptionResponse->getCardType());
    }

    #[Test]
    public function it_can_get_card_number()
    {
        $this->assertSame('**** **** **** 1234', $this->inscriptionResponse->getCardNumber());
    }
}
