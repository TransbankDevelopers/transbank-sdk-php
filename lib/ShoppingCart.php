<?php
namespace Transbank;
/** 
 * 
 * class ShoppingCart
 *
 * @package Transbank
 */

 class ShoppingCart implements \JsonSerializable
 {
    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
    
    public function __construct() 
    {
        $this->items = array();
        $this->total = 0;
    }


    public function getTotal()
    {
        return $this->total;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function add($item)
    {
        $newTotal = $this->total + $item->getAmount();
        if ($newTotal < 0) {
            throw new \Exception("Total amount cannot be less than zero.");
        }

        $this->total = $newTotal;
        array_push($this->items, $item);
        return $this->items;
    }

    public function remove($item)
    {
        $newTotal = $this->total + $item->getAmount();
        if ($newTotal < 0) {
            throw new \Exception("Total amount cannot be less than zero.");
        }
        $itemkey = array_search($item, $this->items);
        array_splice($this->items, $itemkey, 1);
        return $this->items;
    }

    public function getItemQuantity()
    {
        return sizeof($this->items);
    }

    /**
     * Creates a ShoppingCart from an associative array (or json_decode-able string)
     * with the following shape
     * 
     * $cart = array(
     *  "items" => [
     *     ["amount"=> "MANDATORY - A number",
     *      "quantity" => "MANDATORY - A number",
     *      "description" => "MANDATORY - A string",
     *      "additionalData" => "OPTIONAL - A string",
     *      "expire" => "OPTIONAL - A number"],
     *     ...plus whichever number of Item shaped associative arrays you want
     *     to include
     *  ]
     * );
     * 
     */
    public static function fromJSON($cart)
    {
        if(!isset($cart['items']))
        {
            throw new \Exception('Shopping Cart cannot have no items');
        }
        if(!$cart['items'])
        {
            throw new \Exception('Shopping Cart cannot have no items');
        }

        $shoppingCartObject = new ShoppingCart();
        foreach($cart['items'] as $cartItem) {

            $item = Item::fromJSON($cartItem);
            $shoppingCartObject->add($item);
        }
        return $shoppingCartObject;
    }
 }
