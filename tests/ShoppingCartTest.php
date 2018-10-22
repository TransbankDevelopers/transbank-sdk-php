<?php

namespace Transbank\Onepay;

use PHPUnit\Framework\TestCase;

final class ShoppingCartTest extends TestCase
{
    protected function setup()
    {

        $this->cartString = '{"items": [{"amount": 100, "quantity": 10, "description": "something"}, {"amount": 200, "quantity": 20, "description": "something else"}, {"amount": 300, "quantity": 30, "description": "third element"}]}';

    }

    public function testShoppingCartFromJSONThrowsIfParamIsNotJSON()
    {
        $randomString = "definitely not json";
        $this->setExpectedException(\Exception::class, 'Shopping Cart must be a JSON string or an associative array that is transformable to an associative array using json_decode');
        $item = ShoppingCart::fromJSON($randomString);
    }

    public function testSuccessfullyCreatesAShoppingCartFromJSON()
    {

        $cart = ShoppingCart::fromJSON($this->cartString);

        $this->assertTrue($cart instanceof ShoppingCart);
        $this->assertEquals($cart->getItemQuantity(), 60);
        $this->assertEquals($cart->getTotal(), 14000);

        $firstItem = Item::fromJSON('{"amount": 100, "quantity": 10, "description": "something"}');
        $secondItem = Item::fromJSON('{"amount": 200, "quantity": 20, "description": "something else"}');
        $thirdItem = Item::fromJSON('{"amount": 300, "quantity": 30, "description": "third element"}');

        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem));
    }

    public function testCanAddItemsToAShoppingCart()
    {
        $cart = ShoppingCart::fromJSON($this->cartString);
        $this->assertTrue($cart instanceof ShoppingCart);
        $this->assertEquals($cart->getItemQuantity(), 60);
        $this->assertEquals($cart->getTotal(), 14000);

        $firstItem = Item::fromJSON('{"amount": 100, "quantity": 10, "description": "something"}');
        $secondItem = Item::fromJSON('{"amount": 200, "quantity": 20, "description": "something else"}');
        $thirdItem = Item::fromJSON('{"amount": 300, "quantity": 30, "description": "third element"}');

        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem));

        $aNewItem = Item::fromJSON('{"amount": 400, "quantity": 40, "description": "a fourth item"}');
        $cart->add($aNewItem);
        $this->assertEquals($cart->getItemQuantity(), 100);
        $this->assertEquals($cart->getTotal(), 30000);
        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem, $aNewItem));

        $fifthItem =  Item::fromJSON('{"amount": 500, "quantity": 50, "description": "a fifth item"}');
        $cart->add($fifthItem);
        $this->assertEquals($cart->getItemQuantity(), 150);
        $this->assertEquals($cart->getTotal(), 55000);
        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem, $aNewItem, $fifthItem));
    }

    public function testCanAddItemsToAShoppingCartWithItemNegativeValue()
    {
        $cart = new ShoppingCart();

        $firstItem = Item::fromJSON('{"amount": 200, "quantity": 1, "description": "something"}');
        $secondItem = Item::fromJSON('{"amount": -10, "quantity": 1, "description": "discount"}');

        $cart->add($firstItem);
        $cart->add($secondItem);

        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem));
        $this->assertEquals($cart->getItemQuantity(), 2);
        $this->assertEquals($cart->getTotal(), 190);
    }

    public function testCanAddItemsToAShoppingCartWithNegativeValueGreaterThanTotalAmount()
    {
        $cart = new ShoppingCart();

        $firstItem = Item::fromJSON('{"amount": 200, "quantity": 1, "description": "something"}');
        $secondItem = Item::fromJSON('{"amount": -201, "quantity": 1, "description": "discount"}');

        $cart->add($firstItem);
        $this->setExpectedException(\Exception::class, "Total amount cannot be less than zero.");
        $cart->add($secondItem);
    }

    public function testCanRemoveItemsFromAShoppingCart()
    {
        $cart = ShoppingCart::fromJSON($this->cartString);
        $this->assertTrue($cart instanceof ShoppingCart);
        $this->assertEquals($cart->getItemQuantity(), 60);
        $this->assertEquals($cart->getTotal(), 14000);

        $firstItem = Item::fromJSON('{"amount": 100, "quantity": 10, "description": "something"}');
        $secondItem = Item::fromJSON('{"amount": 200, "quantity": 20, "description": "something else"}');
        $thirdItem = Item::fromJSON('{"amount": 300, "quantity": 30, "description": "third element"}');

        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem));

        $aNewItem = Item::fromJSON('{"amount": 400, "quantity": 40, "description": "a fourth item"}');
        $cart->add($aNewItem);
        $this->assertEquals($cart->getItemQuantity(), 100);
        $this->assertEquals($cart->getTotal(), 30000);
        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem, $aNewItem));

        $cart->add($aNewItem);
        $this->assertEquals($cart->getItemQuantity(), 140);
        $this->assertEquals($cart->getTotal(), 46000);
        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem, $aNewItem, $aNewItem));

        /** Start removing items */
        $cart->remove($aNewItem);
        $this->assertEquals($cart->getItemQuantity(), 100);
        $this->assertEquals($cart->getTotal(), 30000);
        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem, $aNewItem));

        $cart->remove($aNewItem);
        $this->assertEquals($cart->getItemQuantity(), 60);
        $this->assertEquals($cart->getTotal(), 14000);
        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem));

        $cart->remove($cart->getItems()[2]);
        $this->assertEquals($cart->getItemQuantity(), 30);
        $this->assertEquals($cart->getTotal(), 5000);
        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem));

        $cart->remove($cart->getItems()[1]);
        $this->assertEquals($cart->getItemQuantity(), 10);
        $this->assertEquals($cart->getTotal(), 1000);
        $this->assertEquals($cart->getItems(), array($firstItem));

        $cart->remove($cart->getItems()[0]);
        $this->assertEquals($cart->getItemQuantity(), 0);
        $this->assertEquals($cart->getTotal(), 0);
        $this->assertEquals($cart->getItems(), array());
    }

    public function testRemoveAllRemovesAll()
    {
        $cart = ShoppingCart::fromJSON($this->cartString);
        $this->assertTrue($cart instanceof ShoppingCart);
        $this->assertEquals($cart->getItemQuantity(), 60);
        $this->assertEquals($cart->getTotal(), 14000);

        $firstItem = Item::fromJSON('{"amount": 100, "quantity": 10, "description": "something"}');
        $secondItem = Item::fromJSON('{"amount": 200, "quantity": 20, "description": "something else"}');
        $thirdItem = Item::fromJSON('{"amount": 300, "quantity": 30, "description": "third element"}');

        $this->assertEquals($cart->getItems(), array($firstItem, $secondItem, $thirdItem));

        $cart->removeAll();
        $this->assertTrue($cart instanceof ShoppingCart);
        $this->assertEquals($cart->getItemQuantity(), 0);
        $this->assertEquals($cart->getTotal(), 0);
        $this->assertEquals($cart->getItems(), array());
    }

    public function testShoppingCartThrowsWhenRemovingAnItemMultipleTimes()
    {
        $cart = ShoppingCart::fromJSON($this->cartString);
        $thirdItem = $cart->getItems()[2];
        $cart->remove($thirdItem);
        $this->setExpectedException(\Exception::class, 'Item not found.');
        $cart->remove($thirdItem);
    }

    public function testShoppingCartCanAddAndThenRemoveTheSameItemInstanceMultipleTimes()
    {
        $cart = ShoppingCart::fromJSON($this->cartString);
        $this->assertEquals($cart->getItemQuantity(), 60);
        $firstItem = $cart->getItems()[0];
        // Add the same first item a second time
        $cart->add($firstItem);
        $this->assertEquals($cart->getItemQuantity(), 70);
        // Add the same first item a third time
        $cart->add($firstItem);
        $this->assertEquals($cart->getItemQuantity(), 80);

        // Now, remove the first item once...
        $cart->remove($firstItem);
        $this->assertEquals($cart->getItemQuantity(), 70);

        // Remove the first item twice...
        $cart->remove($firstItem);
        $this->assertEquals($cart->getItemQuantity(), 60);

        // Remove the first item thrice...
        $cart->remove($firstItem);
        $this->assertEquals($cart->getItemQuantity(), 50);

        // Remove the first item a fourth time, which should fail since there are
        // no more $firstItem instances on the ShoppingCart
        $this->setExpectedException(\Exception::class, 'Item not found.');
        $cart->remove($firstItem);
    }

    public function testShoppingCartShouldNotRemoveAnItemIfItsNotEqualToTheAddedItem()
    {
        $cart = ShoppingCart::fromJSON($this->cartString);
        $this->assertEquals($cart->getItemQuantity(), 60);

        $firstItem = $cart->getItems()[0];
        $this->assertEquals($firstItem->getAmount(), 100);

        $firstItem->setAmount(200);
        $this->assertEquals($firstItem->getAmount(), 200);

        $this->setExpectedException(\Exception::class, 'Item not found.');
        $cart->remove($firstItem);
    }
}
