<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassComercio\Responses\InscriptionStatusResponse;

class InscriptionStatusResponseTest extends TestCase
{
    /** @test */
    public function it_can_be_created_from_json()
    {
        $json = [
            'authorized' => 'status',
            'voucherUrl' => 'urlVoucher',
        ];

        $response = new InscriptionStatusResponse($json);

        $this->assertSame('status', $response->status);
        $this->assertSame('urlVoucher', $response->urlVoucher);
    }

    /** @test */
    public function it_returns_null_when_json_key_does_not_exist()
    {
        $json = [];

        $response = new InscriptionStatusResponse($json);

        $this->assertNull($response->status);
        $this->assertNull($response->urlVoucher);
    }

    /** @test */
    public function it_can_set_and_get_status()
    {
        $response = new InscriptionStatusResponse([]);
        $response->setStatus('testStatus');
        $this->assertSame('testStatus', $response->getStatus());
    }

    /** @test */
    public function it_can_set_and_get_url_voucher()
    {
        $response = new InscriptionStatusResponse([]);
        $response->setUrlVoucher('testUrl');
        $this->assertSame('testUrl', $response->getUrlVoucher());
    }
}