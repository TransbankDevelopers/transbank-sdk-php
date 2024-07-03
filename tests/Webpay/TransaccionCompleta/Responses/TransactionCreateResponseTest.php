<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCreateResponse;

class TransactionCompletareateResponseTest extends TestCase
{
    /** @test */
    public function it_can_get_token()
    {
        $json = [
            'token' => 'testToken',
        ];

        $response = new TransactionCreateResponse($json);

        $this->assertSame('testToken', $response->getToken());
    }

    /** @test */
    public function it_returns_null_when_key_does_not_exist_in_json()
    {
        $json = [];

        $response = new TransactionCreateResponse($json);

        $this->assertNull($response->getToken());
    }
}
