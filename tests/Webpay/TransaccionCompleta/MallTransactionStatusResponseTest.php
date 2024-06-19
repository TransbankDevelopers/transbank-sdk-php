<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionStatusResponse;
use Transbank\Utils\Utils;

class MallTransactionStatusResponseTest extends TestCase
{
    public function testConstructor()
    {
        $json = [
            'buyOrder' => '123456',
            'accounting_date' => '2022-01-01',
            'transaction_date' => '2022-01-01 12:00:00',
            'card_detail' => ['card_number' => '1234567890123456'],
            'details' => [
                ['detail_key1' => 'detail_value1'],
                ['detail_key2' => 'detail_value2']
            ],
        ];

        $response = new MallTransactionStatusResponse($json);

        $this->assertSame($json['buyOrder'], $response->getBuyOrder());
        $this->assertSame($json['accounting_date'], $response->getAccountingDate());
        $this->assertSame($json['transaction_date'], $response->getTransactionDate());
        $this->assertSame($json['card_detail'], $response->getCardDetail());
        $this->assertSame($json['card_detail']['card_number'], $response->getCardNumber());
        $this->assertCount(2, $response->getDetails());
    }

    public function testReturnValueIfExists()
    {
        $array = ['key1' => 'value1', 'key2' => 'value2'];
        $this->assertSame('value1', Utils::returnValueIfExists($array, 'key1'));
        $this->assertSame('value2', Utils::returnValueIfExists($array, 'key2'));
        $this->assertNull(Utils::returnValueIfExists($array, 'key3'));
    }
}
