<?php

use PHPUnit\Framework\TestCase;
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

    /** @test */
    public function it_set_vci()
    {
        $this->transaction->setVci('newVci');
        $this->assertEquals('newVci', $this->transaction->getVci());
    }
