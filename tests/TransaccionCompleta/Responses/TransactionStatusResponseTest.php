<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\TransactionStatusResponse;

class TransactionStatusResponseTest extends TestCase
{
    protected $json;
    protected $response;
    public function setUp(): void
    {
        $this->json = [
        'vci' => 'Some VCI',
        'prepaid_balance' => 100.00
        ];
        $this->response = new TransactionStatusResponse($this->json);
    }
    /** @test */
    public function it_can_get_prepaid_balance()
    {
        $this->assertSame(100.00, $this->response->getPrepaidBalance());
    }

    /** @test */
    public function it_can_get_vci()
    {
        $this->assertSame('Some VCI', $this->response->getVci());
    }
}
