<?php

namespace Transbank;

use PHPUnit\Framework\TestCase;

final class TransactionTest extends TestCase
{
    protected function setup()
    {
        OnePay::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnePay::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        OnePay::setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp");
        OnePay::setCallbackUrl("localhost");
    }

    public function testTransactionWorksWithoutOptions()
    {
        $shoppingCart = new ShoppingCart();

        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart);

        $this->assertEquals($response["responseCode"], "OK");
        $this->assertEquals($response["description"], "OK");
    }
}
