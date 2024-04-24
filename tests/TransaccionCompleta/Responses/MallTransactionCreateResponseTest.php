<?php
use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\MallTransactionCreateResponse;

class MallTransactionCreateResponseTest extends TestCase
{
    public function testGetToken()
    {
        $token = '123456';
        $response = new MallTransactionCreateResponse(['token' => $token]);
        $this->assertEquals($token, $response->getToken());
        $newToken = '654321';
        $response->setToken('654321');
        $this->assertEquals($newToken, $response->getToken());
    }

    public function testTokenProperty()
    {
        $response = new MallTransactionCreateResponse(['token' => '123456']);
        $this->assertEquals('123456', $response->token);
    }
}
