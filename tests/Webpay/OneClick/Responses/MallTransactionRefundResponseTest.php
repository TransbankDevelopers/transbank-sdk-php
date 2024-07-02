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

    public function testConstructorAssignsNullIfNotExists()
    {

        $json = [];
        $json['type'] = 'testType';

        $response = new MallTransactionRefundResponse($json);

        $this->assertNull($response->authorizationCode);
        $this->assertNull($response->authorizationDate);
        $this->assertNull($response->nullifiedAmount);
        $this->assertNull($response->balance);
        $this->assertNull($response->responseCode);
    }

    public function testConstructorThrowsTypeErrorWhenTypeIsNull()
    {
        $this->expectException(TypeError::class);

        $json = [];
        $response = new MallTransactionRefundResponse($json);

        $this->assertNotNull($response);
    }
}
