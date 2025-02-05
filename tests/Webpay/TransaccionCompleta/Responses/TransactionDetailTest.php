<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionDetail;

class TransactionDetailTest extends TestCase
{
    public function testCreateFromArray()
    {
        $data = [
            'amount' => 1000,
            'status' => 'AUTHORIZED',
            'authorization_code' => '123456',
            'payment_type_code' => 'CC',
            'response_code' => 0,
            'installments_number' => 1,
            'installments_amount' => 1000,
            'commerce_code' => '597055555532',
            'buy_order' => 'ordenCompra12345678',
            'balance' => 0,
            'prepaid_balance' => 0,
        ];

        $transactionDetail = TransactionDetail::createFromArray($data);

        $this->assertSame(1000, $transactionDetail->getAmount());
        $this->assertSame($data['status'], $transactionDetail->getStatus());
        $this->assertSame($data['authorization_code'], $transactionDetail->getAuthorizationCode());
        $this->assertSame($data['payment_type_code'], $transactionDetail->getPaymentTypeCode());
        $this->assertSame($data['response_code'], $transactionDetail->getResponseCode());
        $this->assertSame($data['installments_number'], $transactionDetail->getInstallmentsNumber());
        $this->assertSame(1000, $transactionDetail->getInstallmentsAmount());
        $this->assertSame($data['commerce_code'], $transactionDetail->getCommerceCode());
        $this->assertSame($data['buy_order'], $transactionDetail->getBuyOrder());
        $this->assertSame(0, $transactionDetail->getBalance());
        $this->assertSame($data['prepaid_balance'], $transactionDetail->getPrepaidBalance());
        $this->assertSame(true, $transactionDetail->isApproved());
    }

    public function testCreateRejectedFromArray()
    {
        $data = [
            'amount' => 1000,
            'status' => 'FAILED',
            'authorization_code' => '123456',
            'payment_type_code' => 'CC',
            'response_code' => -1,
            'installments_number' => 1,
            'installments_amount' => 1000,
            'commerce_code' => '597055555532',
            'buy_order' => 'ordenCompra12345678',
            'balance' => 0,
            'prepaid_balance' => 0,
        ];

        $transactionDetail = TransactionDetail::createFromArray($data);

        $this->assertSame(1000, $transactionDetail->getAmount());
        $this->assertSame($data['status'], $transactionDetail->getStatus());
        $this->assertSame($data['authorization_code'], $transactionDetail->getAuthorizationCode());
        $this->assertSame($data['payment_type_code'], $transactionDetail->getPaymentTypeCode());
        $this->assertSame($data['response_code'], $transactionDetail->getResponseCode());
        $this->assertSame($data['installments_number'], $transactionDetail->getInstallmentsNumber());
        $this->assertSame(1000, $transactionDetail->getInstallmentsAmount());
        $this->assertSame($data['commerce_code'], $transactionDetail->getCommerceCode());
        $this->assertSame($data['buy_order'], $transactionDetail->getBuyOrder());
        $this->assertSame(0, $transactionDetail->getBalance());
        $this->assertSame($data['prepaid_balance'], $transactionDetail->getPrepaidBalance());
        $this->assertSame(false, $transactionDetail->isApproved());
    }
}
