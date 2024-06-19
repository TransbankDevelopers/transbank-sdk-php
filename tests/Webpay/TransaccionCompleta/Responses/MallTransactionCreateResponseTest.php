<?php
use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionCreateResponse;

class MallTransactionCreateResponseTest extends TestCase
{
    public function testGetToken()
    {
        $token = '123456';
        $response = new MallTransactionCreateResponse(['token' => $token]);
        $this->assertEquals($token, $response->getToken());
    }

    public function testTokenProperty()
    {
        $response = new MallTransactionCreateResponse(['token' => '123456']);
        $this->assertEquals('123456', $response->token);
    }
}
