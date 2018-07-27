<?php
namespace Transbank;
/**
 * 
 * Class Item
 * 
 * @package Transbank
 */

 class Item implements \JsonSerializable
 {

    private $description;
    private $quantity;
    private $amount; /* int */
    private $additionalData;
    private $expire;
    /**
     * Set Description for an instance of Item
     */

    public function __construct($description, $quantity, $amount,
                                $additionalData = null, $expire = 0)
    {
        
        $this->setDescription($description);
        $this->setQuantity($quantity);
        $this->setAmount($amount);
        $this->setAdditionalData($additionalData);
        $this->setExpire($expire);
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }
    
    public function setDescription($description) {
        if (!is_string($description)) {
            throw new \Exception("Description is not a string");
        }
        $this->description = $description;
    }
    /**
     * Get Description for an instance of Item
     */
    public function getDescription() {
        return $this->description;
    }
    /**
     * Set the quantity for an instance of Item
     */
    public function setQuantity($quantity) {
        if (!is_integer($quantity)) {
            throw new \Exception ("quantity must be an Integer");
        }
        $this->quantity = $quantity;
    }
    /**
    * Get the quantity for an instance of Item
    */
    public function getQuantity() {
        return $this->quantity;
    }
    /**
     * Set the quantity for an instance of Item
     */
    public function setAmount($amount) {
        if (!is_integer($amount)) {
            throw new \Exception ("amount must be an Integer");
        }
        $this->amount = $amount;
    }
    /**
     * Get the quantity for an instance of Item
     */
    public function getAmount() {
        return $this->amount;
    }
    
    /**
     * Set the additional data for an instance of Item
     */
    public function setAdditionalData($additionalData) {
        if (is_null($additionalData)) {
            $additionalData = "";
        }
        if (!is_string($additionalData)) {
            throw new \Exception ("Additional Data must be a String");
        }
        $this->additionalData = $additionalData;
    }
    /**
     * Get the additional data for an instance of Item
     */
    public function getAdditionalData($additionalData) {
        return $this->additionalData;
    }
    /**
     * 
     * Set expire for an instance of Item
     */
    public function setExpire($expire) {
        if (!is_long($expire)) {
            throw new \Exception ("expire must be a Long");
        }
        $this->expire = $expire;
    }
    /**
     * 
     * Get expire for an instance of Item
     */
    public function getExpire($expire) {
        return $this->expire;
    }

 }
