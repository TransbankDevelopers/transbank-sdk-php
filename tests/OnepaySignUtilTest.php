<?php

namespace Transbank\Onepay;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/mocks/TransactionCreateRequestMocks.php');
require_once(__DIR__ . '/mocks/TransactionCommitRequestMocks.php');
require_once(__DIR__ . '/mocks/ShoppingCartMocks.php');
require_once(__DIR__ . '/mocks/TransactionCreateResponseMocks.php');
require_once(__DIR__ . '/mocks/TransactionCommitResponseMocks.php');

final class OnepaySignUtilTest extends TestCase
{

    protected function setup()
    {
        OnepayBase::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnepayBase::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        $this->secret = OnepayBase::getSharedSecret();

        $this->transactionCreateRequestTestObject = TransactionCreateRequestMocks::get();
        $this->transactionCreateResponseTestObject = TransactionCreateResponseMocks::get();

        $this->transactionCommitRequestTestObject = TransactionCommitRequestMocks::get();
        $this->transactionCommitResponseTestObject = TransactionCommitResponseMocks::get();

        $this->shoppingCartTestObject = ShoppingCartMocks::get();
    }


    public function testCreatesOrReturnsSingleton()
    {
        $refClass = new \ReflectionClass(OnepaySignUtil::class);

        $this->assertTrue($refClass->hasProperty('instance'));
    
        $singleton = OnepaySignUtil::getInstance();

        $this->assertTrue($singleton instanceof OnepaySignUtil);

        $shouldBeTheSameObject = OnepaySignUtil::getInstance();

        $this->assertTrue($singleton === $shouldBeTheSameObject);
    }

    public function testBuildsValidSignatureForTransactionCreate()
    {
        $transactionCreateRequestTestObject = $this->transactionCreateRequestTestObject;
        // Sanity check, mock object signature should not be null.
        $this->assertNotNull($transactionCreateRequestTestObject);

        $originalSignature = $transactionCreateRequestTestObject->getSignature();

        // Reset the testObject's signature
        $transactionCreateRequestTestObject->setSignature(null);

        OnepaySignUtil::getInstance()->sign($transactionCreateRequestTestObject, $this->secret);

        // Does the signer sign correctly?
        $this->assertEquals($originalSignature, $transactionCreateRequestTestObject->getSignature());
    }

    public function testBuildsValidSignatureForTransactionCommit()
    {
        $transactionCommitRequestTestObject = $this->transactionCommitRequestTestObject;
        // Sanity check, mock object signature should not be null.
        $this->assertNotNull($transactionCommitRequestTestObject);

        $originalSignature = $transactionCommitRequestTestObject->getSignature();

        // Reset the test object's signature
        $transactionCommitRequestTestObject->setSignature(null);
        OnepaySignUtil::getInstance()->sign($transactionCommitRequestTestObject, $this->secret);

        $this->assertEquals($originalSignature, $transactionCommitRequestTestObject->getSignature());
    }

    public function testTryingToSignWhateverElseShouldRaiseSignException()
    {
        $this->setExpectedException(\Transbank\Onepay\Exceptions\SignException::class);
        OnepaySignUtil::getInstance()->sign("a string", $this->secret);
    }

    public function testValidatesValidSignatureOnTransactionCreateResponse()
    {
        $transactionCreateResponse = $this->transactionCreateResponseTestObject;
        $signatureIsValid = OnepaySignUtil::getInstance()->validate($transactionCreateResponse, $this->secret);
        $this->assertTrue($signatureIsValid);
    }

    public function testValidatesInvalidSignatureOnTransactionCreateResponse()
    {
        $transactionCreateResponse = $this->transactionCreateResponseTestObject;
        $transactionCreateResponse->setSignature('totally not a valid signature');
        $signatureIsValid = OnepaySignUtil::getInstance()->validate($transactionCreateResponse, $this->secret);
        $this->assertFalse($signatureIsValid);
    }


    public function testValidatesValidSignatureOnTransactionCommitResponse()
    {
        $transactionCommitResponse = $this->transactionCommitResponseTestObject;
        $signatureIsValid = OnepaySignUtil::getInstance()->validate($transactionCommitResponse, $this->secret);
        $this->assertTrue($signatureIsValid);
    }

    public function testValidatesInvalidSignatureOnTransactionCommitResponse()
    {
        $transactionCommitResponse = $this->transactionCommitResponseTestObject;
        $transactionCommitResponse->setSignature('totally not a valid signature.');
        $signatureIsValid = OnepaySignUtil::getInstance()->validate($transactionCommitResponse, $this->secret);
        $this->assertFalse($signatureIsValid);
    }
}
