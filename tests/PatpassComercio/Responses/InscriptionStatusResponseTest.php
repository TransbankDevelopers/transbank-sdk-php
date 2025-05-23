<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\PatpassComercio\Responses\InscriptionStatusResponse;

class InscriptionStatusResponseTest extends TestCase
{
    protected $inscriptionResponse;
    protected $json;

    public function setUp(): void
    {
        $this->json = [
            'authorized' => 'status',
            'voucherUrl' => 'urlVoucher'
        ];

        $this->inscriptionResponse = new InscriptionStatusResponse($this->json);
    }

    #[Test]
    public function it_can_be_created_from_json()
    {
        $response = new InscriptionStatusResponse($this->json);

        $this->assertSame('status', $response->status);
        $this->assertSame('urlVoucher', $response->urlVoucher);
    }

    #[Test]
    public function it_returns_null_when_json_key_does_not_exist()
    {
        $json = [];

        $response = new InscriptionStatusResponse($json);

        $this->assertNull($response->status);
        $this->assertNull($response->urlVoucher);
    }

    #[Test]
    public function it_can_get_status()
    {
        $this->assertSame('status', $this->inscriptionResponse->getStatus());
    }

    #[Test]
    public function it_can_set_and_get_url_voucher()
    {
        $this->assertSame('urlVoucher', $this->inscriptionResponse->getUrlVoucher());
    }
}
