<?php
namespace Transbank;
/**
 * class OnePaySignUtil;
 * 
 * @package Transbank;
 */

 class OnePaySignUtil {
    // Make this be a singleton class
    protected static $instance = null;
    protected function __construct() { }
    protected function __clone() { }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function sign($transactionCreateRequest, $secret)
    {
        if (!$secret)
        {
            throw new \Exception('Parameter \'$secret\' must not be null');
        }
        if(!$transactionCreateRequest instanceof TransactionCreateRequest)
        {
            throw new \Exception('Parameter \'$transactionCreateRequest\' must be a TransactionCreateRequest');
        }

        $externalUniqueNumberAsString = (string)$transactionCreateRequest->getExternalUniqueNumber();
        $totalAsString = (string)$transactionCreateRequest->getTotal();
        $itemsQuantityAsString = (string)$transactionCreateRequest->getItemsQuantity();
        $issuedAtAsString = (string)$transactionCreateRequest->getIssuedAt();

        $data = mb_strlen($externalUniqueNumberAsString) . $externalUniqueNumberAsString;
        $data .= mb_strlen($totalAsString) . $totalAsString;
        $data .= mb_strlen($itemsQuantityAsString) . $itemsQuantityAsString;
        $data .= mb_strlen($issuedAtAsString) . $issuedAtAsString;
        $data .= mb_strlen(OnePay::getCallbackUrl()) . OnePay::getCallbackUrl();


        $crypted = hash_hmac('sha256', $data, $secret, true);

        $transactionCreateRequest->setSignature(base64_encode($crypted));
        return $transactionCreateRequest;
    }
 }
