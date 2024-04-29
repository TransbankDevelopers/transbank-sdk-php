<?php

namespace Transbank\Webpay\WebpayPlus\Responses;

use Transbank\Utils\Utils;
use Transbank\Utils\TransactionStatusEnum;

class TransactionRefundResponse
{
    /**
     * @var mixed|null
     */
    public $type;
    /**
     * @var mixed|null
     */
    public $authorizationCode;
    /**
     * @var mixed|null
     */
    public $authorizationDate;
    /**
     * @var mixed|null
     */
    public $nullifiedAmount;
    /**
     * @var mixed|null
     */
    public $balance;
    /**
     * @var mixed|null
     */
    public $responseCode;

    /**
     * TransactionRefundResponse constructor.
     *
     * @param $json
     */
    public function __construct($json)
    {
        $this->type = Utils::returnValueIfExists($json, 'type');
        $this->authorizationCode = Utils::returnValueIfExists($json, 'authorization_code');
        $this->authorizationDate = Utils::returnValueIfExists($json, 'authorization_date');
        $this->nullifiedAmount = Utils::returnValueIfExists($json, 'nullified_amount');
        $this->balance = Utils::returnValueIfExists($json, 'balance');
        $this->responseCode = Utils::returnValueIfExists($json, 'response_code');
    }

    public function success()
    {
        return $this->getType() === TransactionStatusEnum::STATUS_REVERSED ||
            ($this->getType() === TransactionStatusEnum::STATUS_NULLIFIED && $this->getResponseCode() === 0);
    }

    /**
     * @return mixed|null
     */
    public function getNullifiedAmount()
    {
        return $this->nullifiedAmount;
    }

    /**
     * @return mixed|null
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return mixed|null
     */
    public function getResponseCode()
    {
        return (int) $this->responseCode;
    }

    /**
     * @return mixed|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed|null
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @return mixed|null
     */
    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

}
