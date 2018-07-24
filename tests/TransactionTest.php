<?php

namespace Transbank;

use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/mocks/ShoppingCartMocks.php');

final class TransactionTest extends TestCase
{
    const EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST = "1532376544050";
    const OCC_TO_COMMIT_TRANSACTION_TEST = "1807829988419927";
    protected function setup()
    {
        OnePay::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnePay::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        OnePay::setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp");
        OnePay::setCallbackUrl("http://localhost:8080/ewallet-endpoints");
    }

    public function testTransactionCreationWorksWithoutOptions()
    {
        $shoppingCart = ShoppingCartMocks::get();
        $response = Transaction::create($shoppingCart);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionCreationWorksWithOptions()
    {
        $shoppingCart = new ShoppingCart();
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
                               "04533c31-fe7e-43ed-bbc4-1c8ab1538afp",
                               "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart);

        $this->assertEquals($response instanceof TransactionCreateResponse, true);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionCommitWorks()
    {
        // Setting commerce data
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
                               "04533c31-fe7e-43ed-bbc4-1c8ab1538afp",
                               "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");

        // commit transaction
        $response = Transaction::commit(
                                        self::OCC_TO_COMMIT_TRANSACTION_TEST,
                                        self::EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST,
                                        $options
                                       );   
        $this->assertEquals($response instanceof TransactionCommitResponse, true);
    }
}
