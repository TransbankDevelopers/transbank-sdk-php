<?php
namespace Transbank;
/** 
 * 
 * class ShoppingCart
 *
 * @package Transbank
 */

 class ShoppingCart
 {

    function __construct() 
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
            throw new Exception("Total amount cannot be less than zero.");
        }

        $this->total = $newTotal;
        array_push($this->items, $item);
        return $this->items;
    }

    public function remove($item)
    {
        $newTotal = $this->total + $item->getAmount();
        if ($newTotal < 0) {
            throw new Exception("Total amount cannot be less than zero.");
        }
        $itemkey = array_search($item, $this->items);
        array_splice($this->items, $itemkey, 1);
        return $this->items;
    }

    public function getItemQuantity()
    {
        return sizeof($this->items);
    }
 }