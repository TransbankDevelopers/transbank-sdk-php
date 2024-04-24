<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\TransactionCreateResponse;

class TransactionCompletareateResponseTest extends TestCase
{
    /** @test */
    public function it_can_create_instance_from_json()
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

    /** @test */
    public function it_can_set_and_get_token()
    {
        $response = new \Transbank\TransaccionCompleta\Responses\TransactionCreateResponse([]);
        $response->setToken('testToken');

        $this->assertSame('testToken', $response->getToken());
    }
}
