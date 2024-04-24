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

    public function testGettersAndSetters()
    {
        $response = new MallTransactionRefundResponse([]);

        $response->setType('testType');
        $this->assertSame('testType', $response->getType());

        $response->setAuthorizationCode('testAuthorizationCode');
        $this->assertSame('testAuthorizationCode', $response->getAuthorizationCode());

        $response->setAuthorizationDate('testAuthorizationDate');
        $this->assertSame('testAuthorizationDate', $response->getAuthorizationDate());

        $response->setNullifiedAmount('testNullifiedAmount');
        $this->assertSame('testNullifiedAmount', $response->getNullifiedAmount());

        $response->setBalance('testBalance');
        $this->assertSame('testBalance', $response->getBalance());

        $response->setResponseCode(200);
        $this->assertSame(200, $response->getResponseCode());
        
    }
}
