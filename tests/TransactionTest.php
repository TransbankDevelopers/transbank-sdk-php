<?php

namespace Transbank;

use PHPUnit\Framework\TestCase;

final class TransactionTest extends TestCase
{
    const EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST = "8934751b-aa9a-45be-b686-1f45b6c45b02";
    const OCC_TO_COMMIT_TRANSACTION_TEST = "1807419329781765";
    protected function setup()
    {
        OnePay::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnePay::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        OnePay::setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp");
        OnePay::setCallbackUrl("http://localhost:8080/ewallet-endpoints");

    
    }

    public function testTransactionCreationWorksWithoutOptions()
    {
        $shoppingCart = new ShoppingCart();

        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart);

        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionCreationWorksWithOptions()
    {
        $shoppingCart = new ShoppingCart();
        $options = new Options();
        $options->setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg")
                ->setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp")
                ->setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");;
     
        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart);

        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionCommitWorks()
    {
        // Setting comerce data
        $options = new Options();
        $options->setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg")
                ->setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp")
                ->setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");

        // commit transaction
        $response = Transaction::commit(
                                        self::OCC_TO_COMMIT_TRANSACTION_TEST,
                                        self::EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST,
                                        $options
                                       );
        $this->assertEquals($response["responseCode"], "OK");
        $this->assertNotNull($response["result"]["authorizationCode"]);
    }



}
