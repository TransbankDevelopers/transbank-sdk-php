<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Webpay\WebpayPlus\Responses\TransactionStatusResponse;

class WebpayStatusResponseTest extends TestCase
{
    private TransactionStatusResponse $transaction;
    public function setUp(): void
    {
        $data = [
            'vci' => 'testVci',
            'amount' => 1000,
            'status' => 'PARTIALLY_NULLIFIED',
            'response_code' => -1
        ];

        $this->transaction = new TransactionStatusResponse($data);
    }

    #[Test]
    public function it_set_vci()
    {
        $this->transaction->setVci('newVci');
        $this->assertEquals('newVci', $this->transaction->getVci());
    }

    #[Test]
    public function it_check_is_approved()
    {
        $this->assertEquals(false, $this->transaction->isApproved());
        $this->transaction->responseCode = 0;
        $this->assertEquals(true, $this->transaction->isApproved());
        $this->transaction->status = 'FAKE_STATE';
        $this->assertEquals(false, $this->transaction->isApproved());
    }
}
