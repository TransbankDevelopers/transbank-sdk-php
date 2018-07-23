<?php
namespace Transbank;

use PHPUnit\Framework\TestCase;

final class RefundTest extends TestCase 
{

    protected function setup()
    {
        OnePay::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnePay::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        OnePay::setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp");
    }

    public function testRefundWorks()
    {
        $options = (new Options())->setApiKey(OnePay::getApiKey())
                                  ->setSharedSecret(OnePay::getSharedSecret());
        $httpResponse = Refund::create(27500, "1807983490979289", "f506a955-800c-4185-8818-4ef9fca97aae",
                                       "623245", $options);
        
        $this->assertEquals($httpResponse->getResponseCode(), 'OK');
        $this->assertEquals($httpResponse->getDescription(), 'OK');
    }
    
    public function testRefundRaisesExceptionWhenInvalid()
    {
        $options = (new Options())->setApiKey(OnePay::getApiKey())
                                  ->setSharedSecret(OnePay::getSharedSecret());

        // It should raise an exception when failing
        $this->setExpectedException(\Transbank\OnePay\Exceptions\RefundCreateException::class);
        $httpResponse = Refund::create(27500, "INVALID OCC", "f506a955-800c-4185-8818-4ef9fca97aae",
                                       "62324511123", $options);
        $this->assertEquals($httpResponse->getResponseCode(), 'INVALID_PARAMS');
        $this->assertEquals($httpResponse->getDescription(), 'Parametros invalidos');
    }
}
