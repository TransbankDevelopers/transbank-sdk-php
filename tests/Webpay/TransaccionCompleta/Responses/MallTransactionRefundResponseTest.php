<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionRefundResponse;

class MallTransactionRefundResponseTest extends TestCase
{
    public function testConstructorAssignsValuesCorrectly()
    {
        $json = [
            'type' => 'testType',
            'authorization_code' => 'testAuthorizationCode',
            'authorization_date' => 'testAuthorizationDate',
            'nullified_amount' => 4,
            'balance' => 2,
            'response_code' => 0,
        ];

        $response = new MallTransactionRefundResponse($json);

        $this->assertSame('testType', $response->type);
        $this->assertSame('testAuthorizationCode', $response->authorizationCode);
        $this->assertSame('testAuthorizationDate', $response->authorizationDate);
        $this->assertSame(4.0, $response->nullifiedAmount);
        $this->assertSame(2.0, $response->balance);
        $this->assertSame(0, $response->responseCode);
    }

    public function testGetters()
    {
        $json = [
            'type' => 'testType',
            'authorization_code' => 'testAuthorizationCode',
            'authorization_date' => 'testAuthorizationDate',
            'nullified_amount' => 4,
            'balance' => 2,
            'response_code' => 10,
        ];

        $response = new MallTransactionRefundResponse($json);

        $this->assertSame('testType', $response->getType());

        $this->assertSame('testAuthorizationCode', $response->getAuthorizationCode());

        $this->assertSame('testAuthorizationDate', $response->getAuthorizationDate());

        $this->assertSame(4.0, $response->getNullifiedAmount());

        $this->assertSame(2.0, $response->getBalance());

        $this->assertSame(10, $response->getResponseCode());
    }
}
