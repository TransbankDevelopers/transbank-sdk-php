<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Oneclick\Responses\MallTransactionRefundResponse;

class OneClickMallTransactionRefundResponseTest extends TestCase
{
    public function testConstructorAssignsValuesCorrectly()
    {
        $json = [
            'type' => 'testType',
            'authorization_code' => 'testAuthorizationCode',
            'authorization_date' => 'testAuthorizationDate',
            'nullified_amount' => 'testNullifiedAmount',
            'balance' => 'testBalance',
            'response_code' => 'testResponseCode',
        ];

        $response = new MallTransactionRefundResponse($json);

        $this->assertSame('testType', $response->type);
        $this->assertSame('testAuthorizationCode', $response->authorizationCode);
        $this->assertSame('testAuthorizationDate', $response->authorizationDate);
        $this->assertSame('testNullifiedAmount', $response->nullifiedAmount);
        $this->assertSame('testBalance', $response->balance);
        $this->assertSame('testResponseCode', $response->responseCode);
    }

    public function testConstructorAssignsNullIfNotExists()
    {
        $json = [];

        $response = new MallTransactionRefundResponse($json);

        $this->assertNull($response->type);
        $this->assertNull($response->authorizationCode);
        $this->assertNull($response->authorizationDate);
        $this->assertNull($response->nullifiedAmount);
        $this->assertNull($response->balance);
        $this->assertNull($response->responseCode);
    }
}
