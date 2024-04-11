<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Oneclick\Responses\InscriptionFinishResponse;
use Transbank\Utils\ResponseCodesEnum;

class OneClickInscriptionFinishResponseTest extends TestCase {
    /** @test */
    public function it_can_be_initialized_from_json()
    {
        $json = [
            'response_code' => '00',
            'tbk_user' => '123456',
            'authorization_code' => '7890',
            'card_type' => 'Visa',
            'card_number' => '**** **** **** 1234'
        ];

        $response = new InscriptionFinishResponse($json);

        $this->assertSame('00', $response->responseCode);
        $this->assertSame('123456', $response->tbkUser);
        $this->assertSame('7890', $response->authorizationCode);
        $this->assertSame('Visa', $response->cardType);
        $this->assertSame('**** **** **** 1234', $response->cardNumber);
    }

    /** @test */
    public function it_returns_true_when_response_code_is_approved()
    {
        $json = ['response_code' => ResponseCodesEnum::RESPONSE_CODE_APPROVED];
        $response = new InscriptionFinishResponse($json);
        $this->assertTrue($response->isApproved());
    }

    /** @test */
    public function it_returns_false_when_response_code_is_not_approved()
    {
        $json = ['response_code' => '01'];
        $response = new InscriptionFinishResponse($json);
        $this->assertFalse($response->isApproved());
    }

    /** @test */
    public function it_can_set_and_get_response_code()
    {
        $response = new InscriptionFinishResponse([]);
        $response->setResponseCode(200);
        $this->assertSame(200, $response->getResponseCode());
    }

    /** @test */
    public function it_can_set_and_get_tbk_user()
    {
        $response = new InscriptionFinishResponse([]);
        $response->setTbkUser('123456');
        $this->assertSame('123456', $response->getTbkUser());
    }

    /** @test */
    public function it_can_set_and_get_authorization_code()
    {
        $response = new InscriptionFinishResponse([]);
        $response->setAuthorizationCode('7890');
        $this->assertSame('7890', $response->getAuthorizationCode());
    }

    /** @test */
    public function it_can_set_and_get_card_type()
    {
        $response = new InscriptionFinishResponse([]);
        $response->setCardType('Visa');
        $this->assertSame('Visa', $response->getCardType());
    }

    /** @test */
    public function it_can_set_and_get_card_number()
    {
        $response = new InscriptionFinishResponse([]);
        $response->setCardNumber('**** **** **** 1234');
        $this->assertSame('**** **** **** 1234', $response->getCardNumber());
    }
}