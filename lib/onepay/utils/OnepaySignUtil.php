<?php
namespace Transbank\Onepay;
use Transbank\Onepay\Exceptions\SignException as SignException;

/**
 * class OnepaySignUtil;
 * 
 * @package Transbank;
 */

 class OnepaySignUtil {
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
            throw new SignException('Parameter \'$secret\' must not be null');
        }

        if ($requestToSign instanceof TransactionCreateRequest) {
            return self::getInstance()->signTransactionCreateRequest($requestToSign, $secret);
        }

        if ($requestToSign instanceof TransactionCommitRequest) {
            return self::getInstance()->signTransactionCommitRequest($requestToSign, $secret);
        }

        if ($requestToSign instanceof RefundCreateRequest) {
            return self::getInstance()->signRefundCreateRequest($requestToSign, $secret);
        }
       throw new SignException('Parameter \'$requestToSign\' must be a TransactionCreateRequest or TransactionCommitRequest');
    }

    private function signTransactionCreateRequest($transactionCreateRequest, $secret)
    {
        $signature = $this->buildSignature($transactionCreateRequest, $secret);
        return $transactionCreateRequest->setSignature($signature);
    }

    private function signTransactionCommitRequest($transactionCommitRequest, $secret)
    {
        $signature = $this->buildSignatureTransactionCommitRequestOrCreateResponse($transactionCommitRequest,
                                                            $secret);
        return $transactionCommitRequest->setSignature($signature);
    }

    private function signRefundCreateRequest($refundCreateRequest, $secret)
    {
        $signature = $this->buildSignatureRefundCreateRequest($refundCreateRequest,
                                                              $secret);
        return $refundCreateRequest->setSignature($signature);
    }


    public function validate($signable, $secret)
    {

        if ($signable instanceof TransactionCreateResponse) {
            $signed = $this->buildSignature($signable, $secret);
        }
        else if ($signable instanceof TransactionCommitResponse) {
            $signed = $this->buildSignature($signable, $secret);
        }
        else {
            throw new SignException('Given signable object is not validatable.');
        }

       return $signable->getSignature() == $signed;
    }
    
    private function buildSignature($signable, $secret)
    {
        if ($signable instanceof TransactionCommitRequest || $signable instanceof TransactionCreateResponse) {
            // Both the TransactionCommitRequest and TransactionCreateResponse
            // build their signatures the same way.
            return $this->buildSignatureTransactionCommitRequestOrCreateResponse($signable, $secret);
        }
        else if ($signable instanceof TransactionCreateRequest) {
            return $this->buildSignatureTransactionCreateRequest($signable, $secret);
        }
        else if ($signable instanceof TransactionCommitResponse) {
            return $this->buildSignatureTransactionCommitResponse($signable, $secret);
        }
        else {
            throw new SignException('Unknown type of signable.');
        }
    }

    private function buildSignatureTransactionCommitRequestOrCreateResponse($signable, $secret)
    {
        if (!$signable instanceof TransactionCommitRequest && !$signable instanceof TransactionCreateResponse) {
            throw new SignException('Invalid type of signable. Type must be TransactionCommitRequest or TransactionCreateResponse');
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

    private function buildSignatureTransactionCreateRequest($signable, $secret)
    {
        if (!$signable instanceof TransactionCreateRequest) {
            throw new SignException('Invalid signable. Accepted type: TransactionCreateRequest');
        }

        $externalUniqueNumberAsString = (string)$signable->getExternalUniqueNumber();
        $totalAsString = (string)$signable->getTotal();
        $itemsQuantityAsString = (string)$signable->getItemsQuantity();
        $issuedAtAsString = (string)$signable->getIssuedAt();

        $data = mb_strlen($externalUniqueNumberAsString) . $externalUniqueNumberAsString;
        $data .= mb_strlen($totalAsString) . $totalAsString;
        $data .= mb_strlen($itemsQuantityAsString) . $itemsQuantityAsString;
        $data .= mb_strlen($issuedAtAsString) . $issuedAtAsString;
        $data .= mb_strlen(OnepayBase::getCallbackUrl()) . OnepayBase::getCallbackUrl();


        $crypted = hash_hmac('sha256', $data, $secret, true);
        return base64_encode($crypted);
    }

    private function buildSignatureTransactionCommitResponse($signable, $secret)
    {
        if(!$signable instanceof TransactionCommitResponse) {
            throw new SignException('Invalid signable. Accepted type: TransactionCommitResponse');
        } 

        $occ = $signable->getOcc();
        $authorizationCode = $signable->getAuthorizationCode();
        $issuedAtAsString = (string)$signable->getIssuedAt();
        $amountAsString = (string)$signable->getAmount();
        $installmentsAmountAsString = (string)$signable->getInstallmentsAmount();
        $installmentsNumberAsString = (string)$signable->getInstallmentsNumber();
        $buyOrder = (string)$signable->getBuyOrder();

        $data = mb_strlen($occ) . $occ;
        $data .= mb_strlen($authorizationCode) . $authorizationCode;
        $data .= mb_strlen($issuedAtAsString) . $issuedAtAsString;
        $data .= mb_strlen($amountAsString) . $amountAsString;
        $data .= mb_strlen($installmentsAmountAsString) . $installmentsAmountAsString;
        $data .= mb_strlen($installmentsNumberAsString) . $installmentsNumberAsString;
        $data .= mb_strlen($buyOrder) . $buyOrder;

        $crypted = hash_hmac('sha256', $data, $secret, true);
        return base64_encode($crypted);
    }

    private function buildSignatureRefundCreateRequest($signable, $secret)
    {
        if (!$signable instanceof RefundCreateRequest)
        {
            throw new SignException('Invalid type of signable. Type must be RefundCreateRequest');
        }

        $occ = $signable->getOcc();
        $externalUniqueNumber = $signable->getExternalUniqueNumber();
        $authorizationCode = $signable->getAuthorizationCode();
        $issuedAtAsString = (string)$signable->getIssuedAt();
        $refundAmountAsString = (string)$signable->getNullifyAmount();

        $data = mb_strlen($occ) . $occ;
        $data .= mb_strlen($externalUniqueNumber) . $externalUniqueNumber;
        $data .= mb_strlen($authorizationCode) . $authorizationCode;
        $data .= mb_strlen($issuedAtAsString) . $issuedAtAsString;
        $data .= mb_strlen($refundAmountAsString) . $refundAmountAsString;
        
        $crypted = hash_hmac('sha256', $data, $secret, true);
        return base64_encode($crypted);
    }



 }
