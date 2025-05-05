<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionStatusResponse;

class TransactionStatusResponseTest extends TestCase
{
    protected array $json;
    protected TransactionStatusResponse $response;
    public function setUp(): void
    {
        $this->json = [
            'vci' => 'Some VCI',
            'prepaid_balance' => 100,
        ];
        $this->response = $this->getMockBuilder(TransactionStatusResponse::class)
            ->setConstructorArgs([$this->json])
            ->onlyMethods(['setTransactionStatusFields'])
            ->getMock();
    }
    #[Test]
    public function it_can_get_prepaid_balance()
    {
        $this->assertSame(100, $this->response->getPrepaidBalance());
    }

    #[Test]
    public function it_can_get_vci()
    {
        $this->assertSame('Some VCI', $this->response->getVci());
    }
}
