<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\TransactionRefundResponse;

class TransactionRefundResponseTest extends TestCase
{
    public function testConstructor()
    {
        $json = [
            'type' => 'testType',
            'authorization_code' => 'testAuthorizationCode',
            'authorization_date' => 'testAuthorizationDate',
            'nullified_amount' => 'testNullifiedAmount',
            'balance' => 'testBalance',
            'response_code' => 'testResponseCode',
        ];

        $response = new TransactionRefundResponse($json);

        $this->assertEquals('testType', $response->type);
        $this->assertEquals('testAuthorizationCode', $response->authorizationCode);
        $this->assertEquals('testAuthorizationDate', $response->authorizationDate);
        $this->assertEquals('testNullifiedAmount', $response->nullifiedAmount);
        $this->assertEquals('testBalance', $response->balance);
        $this->assertEquals('testResponseCode', $response->responseCode);
    }

    public function testGettersAndSetters()
    {
        $response = new TransactionRefundResponse([]);

        $response->setType('testType');
        $this->assertEquals('testType', $response->getType());

        $response->setAuthorizationCode('testAuthorizationCode');
        $this->assertEquals('testAuthorizationCode', $response->getAuthorizationCode());

        $response->setAuthorizationDate('testAuthorizationDate');
        $this->assertEquals('testAuthorizationDate', $response->getAuthorizationDate());

        $response->setNullifiedAmount('testNullifiedAmount');
        $this->assertEquals('testNullifiedAmount', $response->getNullifiedAmount());

        $response->setBalance('testBalance');
        $this->assertEquals('testBalance', $response->getBalance());

        $response->setResponseCode(200);
        $this->assertEquals(200, $response->getResponseCode());        
    }
}
