<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionCreateResponse;

class TransactionCreateResponseTest extends TestCase
{
    /** @test */
    public function it_can_create_instance_from_json()
    {
        $json = [
            'token' => 'testToken',
            'url' => 'testUrl'
        ];

        $response = new TransactionCreateResponse($json);

        $this->assertSame('testToken', $response->getToken());
        $this->assertSame('testUrl', $response->getUrl());
    }

    /** @test */
    public function it_returns_null_when_key_does_not_exist_in_json()
    {
        $json = [
            'token' => 'testToken'
        ];

        $response = new TransactionCreateResponse($json);

        $this->assertSame('testToken', $response->getToken());
        $this->assertNull($response->getUrl());
    }

     /** @test */
     public function it_can_set_and_get_token()
     {
         $response = new TransactionCreateResponse([]);
         $response->setToken('testToken');
 
         $this->assertSame('testToken', $response->getToken());
     }
 
     /** @test */
     public function it_can_set_and_get_url()
     {
         $response = new TransactionCreateResponse([]);
         $response->setUrl('testUrl');
 
         $this->assertSame('testUrl', $response->getUrl());
     }
}
