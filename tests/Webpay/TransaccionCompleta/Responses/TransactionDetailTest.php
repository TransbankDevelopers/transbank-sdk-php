<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionDetail;

class TransactionDetailTest extends TestCase
{
    public function testCreateFromArray()
    {
        $data = [
            'amount' => 1000,
            'status' => 'OK',
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

        $this->assertSame(1000, $transactionDetail->amount);
        $this->assertSame($data['status'], $transactionDetail->status);
        $this->assertSame($data['authorization_code'], $transactionDetail->authorizationCode);
        $this->assertSame($data['payment_type_code'], $transactionDetail->paymentTypeCode);
        $this->assertSame($data['response_code'], $transactionDetail->responseCode);
        $this->assertSame($data['installments_number'], $transactionDetail->installmentsNumber);
        $this->assertSame(1000, $transactionDetail->installmentsAmount);
        $this->assertSame($data['commerce_code'], $transactionDetail->commerceCode);
        $this->assertSame($data['buy_order'], $transactionDetail->buyOrder);
        $this->assertSame(0, $transactionDetail->balance);
        $this->assertSame($data['prepaid_balance'], $transactionDetail->prepaidBalance);
    }
}
