<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\MallTransactionRefundResponse;

class MallTransactionRefundResponseTest extends TestCase
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

    public function testGetters()
    {
        $json = [
            'type' => 'testType',
            'authorization_code' => 'testAuthorizationCode',
            'authorization_date' => 'testAuthorizationDate',
            'nullified_amount' => 'testNullifiedAmount',
            'balance' => 'testBalance',
            'response_code' => 10,
        ];

        $response = new MallTransactionRefundResponse($json);

        $this->assertSame('testType', $response->getType());

        $this->assertSame('testAuthorizationCode', $response->getAuthorizationCode());

        $this->assertSame('testAuthorizationDate', $response->getAuthorizationDate());

        $this->assertSame('testNullifiedAmount', $response->getNullifiedAmount());

        $this->assertSame('testBalance', $response->getBalance());

        $this->assertSame(10, $response->getResponseCode());

    }
}
