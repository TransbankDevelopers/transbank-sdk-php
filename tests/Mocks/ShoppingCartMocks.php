<?php

namespace Tests\Mocks;

use Transbank\Onepay\Item;
use Transbank\Onepay\ShoppingCart;

class ShoppingCartMocks
{
    public static $shoppingCartMocks = [];

    public static function get($indexOfMock = 0)
    {
        return self::shoppingCartMocks()[$indexOfMock];
    }

    public static function shoppingCartMocks()
    {
        if (empty(self::$shoppingCartMocks)) {
            $shoppingCart = new ShoppingCart();

            $firstItem = new Item('Zapatos', 1, 15000, null, -1);
            $secondItem = new Item('Pantalon', 1, 12500, null, -1);

            $shoppingCart->add($firstItem);
            $shoppingCart->add($secondItem);

            array_push(self::$shoppingCartMocks, $shoppingCart);
        }

        return self::$shoppingCartMocks;
    }
}
