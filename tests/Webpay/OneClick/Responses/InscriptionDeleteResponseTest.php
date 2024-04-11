<?php

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Oneclick\Responses\InscriptionDeleteResponse;

class InscriptionDeleteResponseTest extends TestCase
{
    public function testConstructor()
    {
        $response = new InscriptionDeleteResponse(true, 200);

        $this->assertTrue($response->wasSuccessfull());
        $this->assertSame(200, $response->getCode());
    }

    public function testWasSuccessfull()
    {
        $response = new InscriptionDeleteResponse(true);
        $this->assertTrue($response->wasSuccessfull());

        $response = new InscriptionDeleteResponse(false);
        $this->assertFalse($response->wasSuccessfull());
    }

    public function testGetCode()
    {
        $response = new InscriptionDeleteResponse(true, 200);
        $this->assertSame(200, $response->getCode());

        $response = new InscriptionDeleteResponse(true, 404);
        $this->assertSame(404, $response->getCode());
    }
}
