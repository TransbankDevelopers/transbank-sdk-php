<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\WebpayPlus\Responses\TransactionRefundResponse;

class WebpayRefundResponseTest extends TestCase
{
    private TransactionRefundResponse $refund;
    public function setUp(): void
    {
        $data = [
            'type' => 'REVERSED',
            'authorization_code' => 'abc12',
            'authorization_date' => '2014-02-12',
            'nullified_amount' => 1200,
            'balance' => 0,
            'response_code' => 0
        ];

        $this->refund = new TransactionRefundResponse($data);
    }

    /** @test */
    public function it_get_data()
    {
        $this->assertEquals(true, $this->refund->success());
        $this->assertEquals(1200, $this->refund->getNullifiedAmount());
        $this->assertEquals(0, $this->refund->getBalance());
        $this->assertEquals(0, $this->refund->getResponseCode());
        $this->assertEquals('REVERSED', $this->refund->getType());
        $this->assertEquals('abc12', $this->refund->getAuthorizationCode());
        $this->assertEquals('2014-02-12', $this->refund->getAuthorizationDate());
    }
}
