<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassComercio\Responses\InscriptionStartResponse;

class InscriptionStartResponseTest extends TestCase
{
    /** @test */
    public function it_can_set_and_get_token()
    {
        $response = new InscriptionStartResponse([]);
        $response->setToken('testToken');
        $this->assertSame('testToken', $response->getToken());
    }

    /** @test */
    public function it_can_set_and_get_url_webpay()
    {
        $response = new InscriptionStartResponse([]);
        $response->setUrlWebpay('testUrl');
        $this->assertSame('testUrl', $response->getUrlWebpay());
    }
}
