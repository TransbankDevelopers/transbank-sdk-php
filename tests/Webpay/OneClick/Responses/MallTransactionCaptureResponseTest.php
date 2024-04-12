<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Oneclick\Responses\MallTransactionCaptureResponse;

class MallTransactionCaptureResponseTest extends TestCase
{
    public function testConstructor()
    {
        $json = [
            'authorization_code' => '123',
            'authorization_date' => '2022-01-01',
            'captured_amount' => 100,
            'response_code' => 200,
        ];

        $response = new MallTransactionCaptureResponse($json);

        $this->assertSame('123', $response->getAuthorizationCode());
        $this->assertSame('2022-01-01', $response->authorizationDate);
        $this->assertSame(100, $response->capturedAmount);
        $this->assertSame(200, $response->responseCode);
    }

    public function testSetAndGetAuthorizationCode()
    {
        $response = new MallTransactionCaptureResponse([]);
        $response->setAuthorizationCode('456');

        $this->assertSame('456', $response->getAuthorizationCode());
    }
}
