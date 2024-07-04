<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionStatusResponse;
use Transbank\Utils\Utils;

class MallTransactionStatusResponseTest extends TestCase
{
    public function testConstructor()
    {
        $json = [
            'buy_order' => '123456',
            'accounting_date' => '0622',
            'transaction_date' => '2022-01-01 12:00:00',
            'card_detail' => ['card_number' => '1234567890123456'],
            'details' => [
                [
                    'amount' => 500,
                    'status' => 'AUTHORIZED',
                    'authorization_code' => '456456',
                    'payment_type_code' => 'VN',
                    'response_code' => 0,
                    'installments_number' => 1,
                    'installments_amount' => 500,
                    'commerce_code' => '597020000540',
                    'buy_order' => '567890',
                    'balance' => 0,
                    'detail_key1' => 'detail_value1',
                    'detail_key2' => 'detail_value2'
                ]
            ],
        ];

        $response = new MallTransactionStatusResponse($json);

        $this->assertSame($json['buy_order'], $response->getBuyOrder());
        $this->assertSame($json['accounting_date'], $response->getAccountingDate());
        $this->assertSame($json['transaction_date'], $response->getTransactionDate());
        $this->assertSame($json['card_detail'], $response->getCardDetail());
        $this->assertSame($json['card_detail']['card_number'], $response->getCardNumber());
        $jsonDetails = $response->getDetails()[0];
        $this->assertCount(1, $response->getDetails());
        $this->assertSame(500, $jsonDetails->getAmount());
        $this->assertSame('AUTHORIZED', $jsonDetails->getStatus());
        $this->assertSame('456456', $jsonDetails->getAuthorizationCode());
        $this->assertSame('VN', $jsonDetails->getPaymentTypeCode());
        $this->assertSame(0, $jsonDetails->getResponseCode());
        $this->assertSame(1, $jsonDetails->getInstallmentsNumber());
        $this->assertSame(500, $jsonDetails->getInstallmentsAmount());
        $this->assertSame('597020000540', $jsonDetails->getCommerceCode());
        $this->assertSame('567890', $jsonDetails->getBuyOrder());
    }

    public function testReturnValueIfExists()
    {
        $array = ['key1' => 'value1', 'key2' => 'value2'];
        $this->assertSame('value1', Utils::returnValueIfExists($array, 'key1'));
        $this->assertSame('value2', Utils::returnValueIfExists($array, 'key2'));
        $this->assertNull(Utils::returnValueIfExists($array, 'key3'));
    }
}
