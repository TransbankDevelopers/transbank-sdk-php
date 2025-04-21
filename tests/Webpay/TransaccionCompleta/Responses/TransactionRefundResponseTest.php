<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionRefundResponse;

class TransactionRefundResponseTest extends TestCase
{

    protected $json;
    protected $refundResponse;

    public function setUp(): void
    {
        $this->json = [
            'type' => 'testType',
            'authorization_code' => 'testAuthorizationCode',
            'authorization_date' => 'testAuthorizationDate',
            'nullified_amount' => 4,
            'balance' => 2,
            'response_code' => 200,
        ];

        $this->refundResponse = new TransactionRefundResponse($this->json);
    }
    public function testConstructor()
    {

        $response = new TransactionRefundResponse($this->json);

        $this->assertEquals('testType', $response->type);
        $this->assertEquals('testAuthorizationCode', $response->authorizationCode);
        $this->assertEquals('testAuthorizationDate', $response->authorizationDate);
        $this->assertEquals(4, $response->nullifiedAmount);
        $this->assertEquals(2, $response->balance);
        $this->assertEquals(200, $response->responseCode);
    }

    public function testGetters()
    {
        $this->assertEquals('testType', $this->refundResponse->getType());

        $this->assertEquals('testAuthorizationCode', $this->refundResponse->getAuthorizationCode());

        $this->assertEquals('testAuthorizationDate', $this->refundResponse->getAuthorizationDate());

        $this->assertEquals(4, $this->refundResponse->getNullifiedAmount());

        $this->assertEquals(2, $this->refundResponse->getBalance());

        $this->assertEquals(200, $this->refundResponse->getResponseCode());
    }
}
