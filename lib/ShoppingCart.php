<?php
namespace Transbank\Onepay;
/** 
 * 
 * class ShoppingCart
 *
 * @package Transbank
 */

 class ShoppingCart implements \JsonSerializable
 {
    private $items;
    private $total;
    
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
        /**
         * Returns a copy of $this->items so it cannot be modified from the
         * outside
         */
        if(empty($this->items)) {
            return array();
        }
        $newItems = [];

        foreach($this->items as $item) {
            $newItem = clone $item;
            array_push($newItems, $newItem);
        }
        return  $newItems;
    }

    public function add($item)
    {
        $newTotal = $this->total + $item->getAmount() * $item->getQuantity();
        if ($newTotal < 0) {
            throw new \Exception("Total amount cannot be less than zero.");
        }
        array_push($this->items, $item);
        $this->total = $newTotal;
        return true;
    }

    public function remove($item)
    {
        $newTotal = $this->total - $item->getAmount() * $item->getQuantity();
        $itemKey = array_search($item, $this->items);
        if($itemKey === false) {
            throw new \Exception('Item not found.');
        }

        if ($newTotal < 0) {
            throw new \Exception("Total amount cannot be less than zero.");
        }
        array_splice($this->items, $itemKey, 1);
        $this->total = $newTotal;
        return true;
    }

    public function removeAll()
    {
        $this->total = 0;
        $this->items = array();
    }

    public function getItemQuantity()
    {
        $quantity = 0;
        foreach ($this->items as $item) {
            $quantity += $item->getQuantity();
        }
        return $quantity;
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
        if(is_string($cart)) {
            $cart = json_decode($cart, true);
        }
        if (!is_array($cart)) {
            throw new \Exception('Shopping Cart must be a JSON string or an associative array that is transformable to an associative array using json_decode');
        }

        if(!isset($cart['items']))
        {
            throw new \Exception('Shopping Cart must have an "items" key (even if null/empty)');
        }

        if(!$cart['items'])
        {
            return new ShoppingCart();
        }

        if(empty($cart['items']))
        {
            return new ShoppingCart();
        }

        $shoppingCartObject = new ShoppingCart();
        foreach($cart['items'] as $cartItem) {

            $item = Item::fromJSON($cartItem);
            $shoppingCartObject->add($item);
        }
        return $shoppingCartObject;
    }
 }
