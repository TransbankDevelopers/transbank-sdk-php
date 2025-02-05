<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Utils\HasTransactionStatus;

class DummyClass
{
    use HasTransactionStatus;
}

class HasTransactionStatusTest extends TestCase
{
    public function testGetters()
    {

        $date = new DateTime();
        $accountingDateFormat = $date->format('md');
        $transactionDateFormat = $date->format('Y-m-d\TH:i:s.v\Z');
        $json = [
            'amount' => 100,
            'status' => 'AUTHORIZED',
            'buy_order' => '123',
            'session_id' => '123',
            'card_detail' => ['card_number' => '123'],
            'card_number' => '123',
            'accounting_date' => $accountingDateFormat,
            'transaction_date' => $transactionDateFormat,
            'authorization_code' => '123',
            'payment_type_code' => 'VD',
            'response_code' => 200,
            'installments_amount' => 100,
            'installments_number' => 5,
            'balance' => 500
        ];

        $transactionStatus = new DummyClass();
        $transactionStatus->setTransactionStatusFields($json);
        $this->assertSame($transactionDateFormat, $transactionStatus->getTransactionDate());

        $this->assertSame(5, $transactionStatus->getInstallmentsNumber());

        $this->assertSame(100, $transactionStatus->getAmount());

        $this->assertSame('123', $transactionStatus->getBuyOrder());

        $this->assertSame(500, $transactionStatus->getBalance());

        $this->assertSame('AUTHORIZED', $transactionStatus->getStatus());

        $this->assertSame('123', $transactionStatus->getSessionId());

        $this->assertSame('VD', $transactionStatus->getPaymentTypeCode());

        $this->assertSame(100, $transactionStatus->getInstallmentsAmount());

        $this->assertSame(200, $transactionStatus->getResponseCode());

        $this->assertSame(['card_number' => '123'], $transactionStatus->getCardDetail());

        $this->assertSame('123', $transactionStatus->getAuthorizationCode());

        $this->assertSame('123', $transactionStatus->getCardNumber());

        $this->assertSame($accountingDateFormat, $transactionStatus->getAccountingDate());
    }
}
