<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\WebpayPlus\Responses\TransactionDetail;

class WebpayDetailResponseTest extends TestCase
{
    private TransactionDetail $details;

    public function setUp(): void
    {
        $this->details = new TransactionDetail();
        $this->details->responseCode = -1;
        $this->details->status = 'AUTHORIZED';
        $this->details->balance = 100;
    }

    /** @test */
    public function it_checks_is_approved()
    {
        $this->assertFalse($this->details->isApproved());
        $this->details->responseCode = 0;
        $this->assertTrue($this->details->isApproved());
        $this->details->status = 'FAKE_STATUS';
        $this->assertFalse($this->details->isApproved());
    }

    /** @test */
    public function it_can_get_balance()
    {
        $this->assertEquals(100, $this->details->getBalance());
    }
}
