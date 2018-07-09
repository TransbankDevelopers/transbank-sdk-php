<?php

namespace Transbank;

use PHPUnit\Framework\TestCase;

final class TransactionTest extends TestCase
{

    public function testItWorks()
    {
        echo "Test is running!";
        
        $this->assertEquals(
            true,
            true
        );
    }

    protected function setup()
    {
        OnePay::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnePay::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        OnePay::setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp");
        OnePay::setCallbackUrl("http://localhost:8080/ewallet-endpoints");
    }

    public function testTransactionWorks()
    {



        $shoppingCart = new ShoppingCart();

        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart);

        echo "transaction created";
        echo json_encode($response);







    }




//        // Setting comerce data
//        Onepay.setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
//        Onepay.setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
//        Onepay.setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp");
//        Onepay.setCallbackUrl("http://localhost:8080/ewallet-endpoints");

//        // Setting items to the shopping cart
//        ShoppingCart cart = new ShoppingCart();
//        cart.add(new Item("Zapatos", 1, 15000, null, -1));
//        cart.add(new Item("Pantalon", 1, 12500, null, -1));

//        // Send transaction to Transbank
//        TransactionCreateResponse response = Transaction.create(cart);

//        // Print response
//        System.out.println(response);

//        assert null != response && response.getResponseCode().equalsIgnoreCase("ok")
//                && null != response.getResult() && null != response.getResult().getQrCodeAsBase64();
//    }

//    public void testSendTransactionSecondWay() throws AmountException, IOException, NoSuchAlgorithmException, InvalidKeyException {
//        // Setting comerce data
//        Onepay.setCallbackUrl("http://localhost:8080/ewallet-endpoints");

//        // Setting items to the shopping cart
//        ShoppingCart cart = new ShoppingCart();
//        cart.add(new Item("Porotos Wasil", 1, 990, null, -1));
//        cart.add(new Item("Confort", 1, 1500, null, -1));

//        // Setting comerce data
//        Options options = new Options()
//                .setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg")
//                .setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp")
//                .setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
//        // Send transaction to Transbank
//        TransactionCreateResponse response = Transaction.create(cart, options);

//        // Print response
//        System.out.println(response);

//        assert null != response && response.getResponseCode().equalsIgnoreCase("ok")
//                && null != response.getResult() && null != response.getResult().getQrCodeAsBase64();
//    }












}
