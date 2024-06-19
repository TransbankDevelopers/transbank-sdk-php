<?php

use PHPUnit\Framework\TestCase;
use Transbank\PatpassComercio\Responses\InscriptionStartResponse;

class InscriptionStartResponseTest extends TestCase
{
    protected $inscriptionStartResponse;

    public function setUp(): void
    {
        $json = [
            'token' => 'testToken',
            'url' => 'testUrl'
            ];

        $this->inscriptionStartResponse = new InscriptionStartResponse($json);

    }

    /** @test */
    public function it_can_set_and_get_token()
    {
        $this->assertSame('testToken', $this->inscriptionStartResponse->getToken());
    }

    /** @test */
    public function it_can_set_and_get_url_webpay()
    {
        $this->assertSame('testUrl', $this->inscriptionStartResponse->getUrlWebpay());
    }
}
