<?php

use PHPUnit\Framework\TestCase;
use Transbank\Patpass\PatpassByWebpay\Responses\TransactionCommitResponse;

class TransactionCommitResponseTest extends TestCase
{
    public function testSetAndGetVci()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setVci('testVci');
        $this->assertSame('testVci', $transactionCommitResponse->getVci());
    }

    public function testSetAndGetAmount()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setAmount(100);
        $this->assertSame(100, $transactionCommitResponse->getAmount());
    }

    public function testSetAndGetStatus()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setStatus('testStatus');
        $this->assertSame('testStatus', $transactionCommitResponse->getStatus());
    }

    public function testSetAndGetBuyOrder()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setBuyOrder('testBuyOrder');
        $this->assertSame('testBuyOrder', $transactionCommitResponse->getBuyOrder());
    }

    public function testSetAndGetSessionId()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setSessionId('testSessionId');
        $this->assertSame('testSessionId', $transactionCommitResponse->getSessionId());
    }

    public function testSetAndGetCardDetail()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setCardDetail('testCardDetail');
        $this->assertSame('testCardDetail', $transactionCommitResponse->getCardDetail());
    }

    public function testSetAndGetAccountingDate()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setAccountingDate('testAccountingDate');
        $this->assertSame('testAccountingDate', $transactionCommitResponse->getAccountingDate());
    }

    public function testSetAndGetTransactionDate()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setTransactionDate('testTransactionDate');
        $this->assertSame('testTransactionDate', $transactionCommitResponse->getTransactionDate());
    }
    
    public function testSetAndGetAuthorizationCode()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setAuthorizationCode('testAuthorizationCode');
        $this->assertSame('testAuthorizationCode', $transactionCommitResponse->getAuthorizationCode());
    }

    public function testSetAndGetPaymentTypeCode()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setPaymentTypeCode('testPaymentTypeCode');
        $this->assertSame('testPaymentTypeCode', $transactionCommitResponse->getPaymentTypeCode());
    }

    public function testSetAndGetInstallmentsNumber()
    {
        $transactionCommitResponse = new TransactionCommitResponse([]);
        $transactionCommitResponse->setInstallmentsNumber(3);
        $this->assertSame(3, $transactionCommitResponse->getInstallmentsNumber());
    }
}
