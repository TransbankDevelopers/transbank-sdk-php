<?php
namespace Transbank\Onepay;
/**
 *  @class TransactionRequest
 *  Creates an object to be used when making a transaction request to Onepay
 * @package Transbank;
 */

class TransactionCreateRequest extends BaseRequest implements \JsonSerializable {
    private $externalUniqueNumber; # Number not null
    private $total; # Number not null
    private $itemsQuantity; # Number not null
    private $items; # Array not null
    private $callbackUrl; # String not null
    private $channel; # String not null
    private $appScheme;
    private $signature; # String
    private $generateOttQrCode = true;
    private $commerceLogoUrl;

    function __construct($externalUniqueNumber, $total, $itemsQuantity, $issuedAt,
                         $items, $callbackUrl = null, $channel = 'WEB',
                         $appScheme = null, $widthHeight, $commerceLogoUrl)
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
        $this->issuedAt = $issuedAt;

        if (!$callbackUrl) { throw new \Exception('callbackUrl cannot be null'); }
        $this->callbackUrl = $callbackUrl;

        if(!$channel) {
            throw new \Exception('channel cannot be null.');
        }
        $this->channel = $channel;

        if (null == $appScheme) {
            $appScheme = '';
        }
        $this->appScheme = $appScheme;

        // Do not set the property, since sending null will make Transbank's
        // API respond with an generic error message "Error inesperado"
        if (!$widthHeight == null) {
            $this->widthHeight = $widthHeight;
        }
        $this->commerceLogoUrl = $commerceLogoUrl;
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
        return $this;
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
        return $this;
    }

    public function getItemsQuantity()
    {
        return $this->itemsQuantity;
    }

    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    public function setIssuedAt($issuedAt)
    {
        $this->issuedAt = $issuedAt;
        return $this;
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
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setCallbackUrl($url)
    {
        if (!$url) { throw new \Exception('url cannot be null'); }
        $this->callbackUrl = $url;
        return $this;
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
        return $this;
    }

    public function getChannel($channel)
    {
        return $this->channel;
    }

    /**
     * @return null|string
     */
    public function getAppScheme()
    {
        return $this->appScheme;
    }

    /**
     * @param null|string $appScheme
     * @return TransactionCreateRequest
     */
    public function setAppScheme($appScheme)
    {
        $this->appScheme = $appScheme;
        return $this;
    }


    public function setSignature($signature)
    {
        $this->signature = $signature;
        return $this;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function setWidthHeight($widthHeight)
    {
        if ($widthHeight != null) {
            $this->widthHeight = $widthHeight;
            return $this;
        } else {
            throw new \Exception('WidthHeight cannot be null.');
        }
    }

    public function getWidthHeight()
    {
        if (isset($this->widthHeight))
            return $this->widthHeight;

        return null;
    }

    public function setCommerceLogoUrl($commerceLogoUrl)
    {
        $this->commerceLogoUrl = $commerceLogoUrl;
        return $this;
    }

    public function getCommerceLogoUrl()
    {
        return $this->commerceLogoUrl;
    }
}
