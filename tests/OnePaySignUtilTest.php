<?php

namespace Transbank;

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/mocks/TransactionCreateRequestMocks.php');
require_once(__DIR__ . '/mocks/TransactionCommitRequestMocks.php');
final class OnePaySignUtilTest extends TestCase
{

    protected function setup()
    {
        OnePay::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnePay::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        OnePay::setAppKey("04533c31-fe7e-43ed-bbc4-1c8ab1538afp");
        OnePay::setCallbackUrl("http://localhost:8080/ewallet-endpoints");
        $this->secret = OnePay::getSharedSecret();
        $this->transactionCreateRequestTestObject = TransactionCreateRequestMocks::get();
        $this->transactionCommitRequestTestObject = TransactionCommitRequestMocks::get();
    }


    public function testCreatesOrReturnsSingleton()
    {
        $refClass = new \ReflectionClass(OnePaySignUtil::class);

        $this->assertTrue($refClass->hasProperty('instance'));
    
        $singleton = OnePaySignUtil::getInstance();

        $this->assertTrue($singleton instanceof OnePaySignUtil);

        $shouldBeTheSameObject = OnePaySignUtil::getInstance();

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

        OnePaySignUtil::getInstance()->sign($transactionCreateRequestTestObject, $this->secret);

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
        OnePaySignUtil::getInstance()->sign($transactionCommitRequestTestObject, $this->secret);

        $this->assertEquals($originalSignature, $transactionCommitRequestTestObject->getSignature());
    }

    
    public function testSetsSignatureOnTransactionCreateRequest()
    {

        throw new \Exception('implement me pls');

    }

    public function testSetsSignatureOnTransactionCommitRequest()
    {
        throw new \Exception('implement me pls');


    }

    public function testTryingToSignWhateverElseShouldRaiseSignException()
    {
        throw new \Exception('implement me pls');


    }

    public function testValidatesSignatureOnTransactionCreateRequest()
    {
        throw new \Exception('implement me pls');


    }


}