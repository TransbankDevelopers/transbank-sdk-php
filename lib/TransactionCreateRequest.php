<?php
namespace Transbank;
/** 
 *  @class TransactionRequest
 *  Creates an object to be used when making a transaction request to onepay
 * @package Transbank;
 */

class TransactionCreateRequest extends BaseRequest implements \JsonSerializable {
    private $externalUniqueNumber; # Number not null
    private $total; # Number not null
    private $itemsQuantity; # Number not null
    private $items; # Array not null
    private $callbackUrl; # String not null
    private $channel; # String not null
    private $signature; # String

    function __construct($externalUniqueNumber, $total, $itemsQuantity, 
                        $items, $callbackUrl, $channel) 
    {
        if (!$externalUniqueNumber) { 
            throw new \Exception('External unique number cannot be null.');
        }
        $this->externalUniqueNumber = $externalUniqueNumber;

        if (!$total) { 
            throw new \Exception('Total cannot be null.');
        }
        if ($total < 0) {
            throw new \Exception('Total cannot be less than zero.');
        }
        $this->total = $total;

        if (!$itemsQuantity) { 
            throw new \Exception('Items quantity cannot be null.');
        }
        if ($itemsQuantity < 0) {
            throw new \Exception('Items quantity cannot be less than zero.');
        }
        $this->itemsQuantity = $itemsQuantity;

        if (!is_array($items)) {
            throw new \Exception('Items must be an array.');
        }
        if (empty($items)) {
            throw new \Exception('Items must not be empty.');
        }

        $this->items = $items;

        if (!$url) { throw new \Exception('url cannot be null'); }
        $this->callbackUrl = $url;

        if(!$channel) {
            throw new \Exception('channel cannot be null.');
        }
        $this->channel = $channel;
    }

    public function jsonSerialize() 
    {
        return get_object_vars($this);
    }

    public function setExternalUniqueNumber($externalUniqueNumber)
    {
        if (!$externalUniqueNumber) { 
            throw new \Exception('External unique number cannot be null.');
        }
        $this->externalUniqueNumber = $externalUniqueNumber;
    }

    public function getExternalUniqueNumber()
    {
        return $this->externalUniqueNumber;
    }

    public function setTotal($total)
    {
        if (!$total) { 
            throw new \Exception('Total cannot be null.');
        }
        if ($total < 0) {
            throw new \Exception('Total cannot be less than zero.');
        }
        $this->total = $total;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setItemsQuantity($itemsQuantity)
    {
        if (!$itemsQuantity) { 
            throw new \Exception('Items quantity cannot be null.');
        }
        if ($itemsQuantity < 0) {
            throw new \Exception('Items quantity cannot be less than zero.');
        }
        $this->itemsQuantity = $itemsQuantity;
    }

    public function getItemsQuantity()
    {
        return $this->itemsQuantity;
    }
    
    public function setItems($items)
    {
        if (!is_array($items)) {
            throw new \Exception('Items must be an array.');
        }
        if (empty($items)) {
            throw new \Exception('Items must not be empty.');
        }

        $this->items = $items;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setCallbackUrl($url)
    {
        if (!$url) { throw new \Exception('url cannot be null'); }
        $this->callbackUrl = $url;
    }

    public function getCallbackUrl() 
    {
        return $this->callbackUrl;
    }

    public function setChannel($channel)
    {
        if(!$channel) {
            throw new \Exception('channel cannot be null.');
        }
        $this->channel = $channel;
    }

    public function getChannel($channel) 
    {
        return $this->channel;
    }

}