<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionCommitResponse;

class TransactionCommitResponseTest extends TestCase
{

    protected $commitResponse;

    public function setUp(): void
    {
       $json = [
        'vci' => 'testVci',
        'amount' => 100,
        'status' => 'testStatus',
        'buy_order' => 'testBuyOrder',
        'session_id' => 'testSessionId',
        'card_detail' => 'testCardDetail',
        'accounting_date' => 'testAccountingDate',
        'transaction_date' => 'testTransactionDate',
        'authorization_code' => 'testAuthorizationCode',
        'payment_type_code' => 'testPaymentTypeCode',
        'installments_number' => 3
        ];

        $this->commitResponse = new TransactionCommitResponse($json);
    }
    public function testSetAndGetVci()
    {
        $this->assertSame('testVci', $this->commitResponse->getVci());
    }

    public function testSetAndGetAmount()
    {
        $this->assertSame(100, $this->commitResponse->getAmount());
    }

    public function testSetAndGetStatus()
    {
        $this->assertSame('testStatus', $this->commitResponse->getStatus());
    }

    public function testSetAndGetBuyOrder()
    {
        $this->assertSame('testBuyOrder', $this->commitResponse->getBuyOrder());
    }

    public function testSetAndGetSessionId()
    {
        $this->assertSame('testSessionId', $this->commitResponse->getSessionId());
    }

    public function testSetAndGetCardDetail()
    {
        $this->assertSame('testCardDetail', $this->commitResponse->getCardDetail());
    }

    public function testSetAndGetAccountingDate()
    {
        $this->assertSame('testAccountingDate', $this->commitResponse->getAccountingDate());
    }

    public function testSetAndGetTransactionDate()
    {
        $this->assertSame('testTransactionDate', $this->commitResponse->getTransactionDate());
    }

    public function testSetAndGetAuthorizationCode()
    {
        $this->assertSame('testAuthorizationCode', $this->commitResponse->getAuthorizationCode());
    }

    public function testSetAndGetPaymentTypeCode()
    {
        $this->assertSame('testPaymentTypeCode', $this->commitResponse->getPaymentTypeCode());
    }

    public function testSetAndGetInstallmentsNumber()
    {
        $this->assertSame(3, $this->commitResponse->getInstallmentsNumber());
    }
}
