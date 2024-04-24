<?php

use PHPUnit\Framework\TestCase;
use Transbank\Utils\HasTransactionStatus;

class DummyClass {
    use HasTransactionStatus;
}

class HasTransactionStatusTest extends TestCase
{
    public function testSettersAndGetters()
    {
        $date = new DateTime();
        $transactionStatus = new DummyClass();
        $transactionStatus->setTransactionDate($date);
        $this->assertSame($date, $transactionStatus->getTransactionDate());

        $transactionStatus->setInstallmentsNumber(5);
        $this->assertSame(5, $transactionStatus->getInstallmentsNumber());

        $transactionStatus->setAmount(100);
        $this->assertSame(100, $transactionStatus->getAmount());

        $transactionStatus->setBuyOrder('123');
        $this->assertSame('123', $transactionStatus->getBuyOrder());

        $transactionStatus->setBalance(500);
        $this->assertSame(500, $transactionStatus->getBalance());

        $transactionStatus->setStatus('AUTHORIZED');
        $this->assertSame('AUTHORIZED', $transactionStatus->getStatus());

        $transactionStatus->setSessionId('123');
        $this->assertSame('123', $transactionStatus->getSessionId());

        $transactionStatus->setPaymentTypeCode('VD');
        $this->assertSame('VD', $transactionStatus->getPaymentTypeCode());

        $transactionStatus->setInstallmentsAmount(100);
        $this->assertSame(100, $transactionStatus->getInstallmentsAmount());

        $transactionStatus->setResponseCode(200);
        $this->assertSame(200, $transactionStatus->getResponseCode());

        $transactionStatus->setCardDetail('123');
        $this->assertSame('123', $transactionStatus->getCardDetail());

        $transactionStatus->setAuthorizationCode('123');
        $this->assertSame('123', $transactionStatus->getAuthorizationCode());

        $transactionStatus->setCardNumber('123');
        $this->assertSame('123', $transactionStatus->getCardNumber());
        
        $transactionStatus->setAccountingDate($date);
        $this->assertSame($date, $transactionStatus->getAccountingDate());
    }
}
