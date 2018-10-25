<?php
namespace Transbank\Onepay;
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
        if ($quantity < 0) {
            throw new \Exception ("quantity cannot be less than zero");
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
    public function getAdditionalData() {
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
    public function getExpire() {
        return $this->expire;
    }

    /**
     * Takes a associative array (or JSON string)
     * with the following shape:
     * $item = ("description" => "MANDATORY - A string",
     *          "amount" => "MANDATORY - A number",
     *          "quantity" => "MANDATORY - A number",
     *          "additionalData" => "OPTIONAL - A string",
     *          "expire" => "OPTIONAL - A number")
     *  and creates a new instance of Item
     */
    public static function fromJSON($item)
    {
        if(is_string($item)) {
            $item = json_decode($item, true);
        }
        if (!is_array($item)) {
            throw new \Exception('Item must be a JSON string or an associative array that is transformable to an associative array using json_decode');
        }
        /**
         * Define default values
         */
        $defaultValues = array("amount" => null,
                                "description" => null,
                                "quantity" => null,
                                "expire" => 0,
                                "additionalData" => null);

        /**
         * Have the default values for all keys, event if they are not included
         * in the $item associative array originally.
         */
        $item = array_merge($defaultValues, $item);

        return new Item($item["description"], $item["quantity"],
                        $item["amount"], $item["additionalData"],
                        $item["expire"]);
    }
 }
