<?php
use PHPUnit\Framework\TestCase;
use Transbank\Common\Responses\BaseDeferredStatusResponse;

class BaseDeferredStatusResponseTest extends TestCase
{
    public function testGetAuthorizationCode()
    {
        $response = new BaseDeferredStatusResponse(['authorization_code' => '123456']);
        $this->assertEquals('123456', $response->authorizationCode);
    }

    public function testSetAuthorizationCode()
    {
        $response = new BaseDeferredStatusResponse([]);
        $response->setAuthorizationCode('123456');
        $this->assertEquals('123456', $response->authorizationCode);
    }

    public function testGetAuthorizationDate()
    {
        $date = date('Y-m-d');
        $response = new BaseDeferredStatusResponse(['authorization_date' => $date]);
        $this->assertEquals($date, $response->authorizationDate);
    }

    public function testSetAuthorizationDate()
    {
        $date = date('Y-m-d');
        $response = new BaseDeferredStatusResponse([]);
        $response->setAuthorizationDate($date);
        $this->assertEquals($date, $response->authorizationDate);
    }

    public function testGetTotalAmount()
    {
        $response = new BaseDeferredStatusResponse(['total_amount' => 100.00]);
        $this->assertEquals(100.00, $response->totalAmount);
    }

    public function testSetTotalAmount()
    {
        $response = new BaseDeferredStatusResponse([]);
        $response->setTotalAmount(100.00);
        $this->assertEquals(100.00, $response->totalAmount);
    }

    public function testGetExpirationDate()
    {
        $date = date('Y-m-d');
        $response = new BaseDeferredStatusResponse(['expiration_date' => $date]);
        $this->assertEquals($date, $response->expirationDate);
    }

    public function testSetExpirationDate()
    {
        $date = date('Y-m-d');
        $response = new BaseDeferredStatusResponse([]);
        $response->setExpirationDate($date);
        $this->assertEquals($date, $response->expirationDate);
    }

    public function testGetResponseCode()
    {
        $response = new BaseDeferredStatusResponse(['response_code' => '00']);
        $this->assertEquals('00', $response->responseCode);
    }

    public function testSetResponseCode()
    {
        $response = new BaseDeferredStatusResponse([]);
        $response->setResponseCode('00');
        $this->assertEquals('00', $response->responseCode);
    }
}
