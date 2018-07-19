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

    public function sign($requestToSign, $secret)
    {
        if (!$secret)
        {
            throw new \Exception('Parameter \'$secret\' must not be null');
        }

        if ($requestToSign instanceof TransactionCreateRequest) {
            return self::getInstance()->signTransactionCreateRequest($requestToSign, $secret);
        }

        if ($requestToSign instanceof TransactionCommitRequest) {
            return self::getInstance()->signTransactionCommitRequest($requestToSign, $secret);
        }


        if(!$transactionCreateRequest instanceof TransactionCreateRequest)
        {
            throw new \Exception('Parameter \'$transactionCreateRequest\' must be a TransactionCreateRequest');
        }
    }

    private function signTransactionCreateRequest($transactionCreateRequest, $secret)
    {
        $signature = $this->buildSignature($transactionCreateRequest, $secret);
        // Setters of TransactionCreateRequest return the modified object.
        return $transactionCreateRequest->setSignature($signature);
    }

    private function signTransactionCommitRequest($transactionCommitRequest, $secret)
    {
        $signature = $this->buildSignatureTransactionCommit($transactionCommitRequest,
                                                            $secret);
        // TransactionCommitRequest setters return the object after execution.
        return $transactionCommitRequest->setSignature($signature);
    }


    public function validate($signable, $secret)
    {
        if ($signable instanceof TransactionCommitRequest 
            || $signable instanceof TransactionCreateResponse
            || $signable instanceof TransactionCreateRequest) {
            $signed = $this->buildSignature($signable, $secret);
        }
        else {
            throw new SignException('Unknown type of signable.');
        }

        return $signable->getSignature() == $signed;
    }
    

    private function buildSignature($signable, $secret)
    {
        if ($signable instanceof TransactionCommitRequest || $signable instanceof TransactionCreateResponse) {
            // Both the TransactionCommitRequest and TransactionCreateResponse
            // build their signatures the same way.
            return $this->buildSignatureTransactionCommit($signable, $secret);
        }
        if ($signable instanceof TransactionCreateRequest) {
            return $this->buildSignatureTransactionCreate($signable, $secret);
        }
        throw new SignException('Unknown type of signable.');
    }

    private function buildSignatureTransactionCommit($signable, $secret)
    {
        if (!$signable instanceof TransactionCommitRequest && !$signable instanceof TransactionCreateResponse) {
            throw new SignException('Unknown type of signable.');
        }

        $occ = $signable->getOcc();
        $externalUniqueNumber = $signable->getExternalUniqueNumber();
        $issuedAtAsString = (string)$signable->getIssuedAt();

        if (!$occ || !$externalUniqueNumber) {
            throw new SignException('occ / externalUniqueNumber cannot be null.');
        }

        $data = mb_strlen($occ) . $occ;
        $data .= mb_strlen($externalUniqueNumber) . $externalUniqueNumber;
        $data .= mb_strlen($issuedAtAsString) . $issuedAtAsString;
        return base64_encode(hash_hmac('sha256', $data, $secret, true));
    }

    private function buildSignatureTransactionCreate($signable, $secret)
    {
        if (!$signable instanceof TransactionCreateRequest) {
            throw new SignException('Unknown type of signable.');
        }

        $externalUniqueNumberAsString = (string)$signable->getExternalUniqueNumber();
        $totalAsString = (string)$signable->getTotal();
        $itemsQuantityAsString = (string)$signable->getItemsQuantity();
        $issuedAtAsString = (string)$signable->getIssuedAt();

        $data = mb_strlen($externalUniqueNumberAsString) . $externalUniqueNumberAsString;
        $data .= mb_strlen($totalAsString) . $totalAsString;
        $data .= mb_strlen($itemsQuantityAsString) . $itemsQuantityAsString;
        $data .= mb_strlen($issuedAtAsString) . $issuedAtAsString;
        $data .= mb_strlen(OnePay::getCallbackUrl()) . OnePay::getCallbackUrl();


        $crypted = hash_hmac('sha256', $data, $secret, true);
        return base64_encode($crypted);
    }

 }
