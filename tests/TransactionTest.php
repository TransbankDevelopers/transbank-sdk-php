<?php

namespace Transbank\Onepay;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/mocks/ShoppingCartMocks.php');
require_once(__DIR__ . '/mocks/TransactionCreateResponseMocks.php');
use Transbank\Onepay\Exceptions\TransactionCreateException;
use Transbank\Onepay\Exceptions\TransactionCommitException;
use Transbank\Onepay\Exceptions\SignException;

final class TransactionTest extends TestCase
{
    const EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST = "1532376544050";
    const OCC_TO_COMMIT_TRANSACTION_TEST = "1807829988419927";
    protected function setup()
    {
        OnepayBase::setSharedSecret("P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        OnepayBase::setApiKey("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg");
        OnepayBase::setCurrentIntegrationType("MOCK");
    }

    public function testTransactionRaisesWhenResponseIsNull() {

        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(null);

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Execute the transaction expecting it to raise TransactionCreateException
        // because the mock will make the HttpClient return Null
        $shoppingCart = ShoppingCartMocks::get();

        // This should raise a TransactionCreateException
        try {
            $this->setExpectedException(TransactionCreateException::class, 'Could not obtain a response from the service');
            $response = Transaction::create($shoppingCart);
        }
        finally {
            // Reset the HttpClient static property to its original state
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }
    }

    public function testTransactionRaisesWhenResponseIsNotOk()
    {
        $mockResponse = json_encode(array('responseCode' => 'INVALID_PARAMS',
            'description' => 'Parametros invalidos',
            'result' => null));

        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(new Response(200, [], $mockResponse));

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Execute the transaction expecting it to raise TransactionCreateException
        // because the mock will make the HttpClient return Null
        $shoppingCart = ShoppingCartMocks::get();

        // This should raise a TransactionCreateException
        try {
            $this->setExpectedException(TransactionCreateException::class, 'INVALID_PARAMS : Parametros invalidos');
            $response = Transaction::create($shoppingCart);
        }
        finally {
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }
    }

    public function testTransactionRaisesWhenSignatureIsInvalid()
    {
        $mockResponse =  '{
            "responseCode": "OK",
            "description": "OK",
            "result": {
                "occ": "1807216892091979",
                "ott": 51435450,
                "signature": "FAKE SIGNATURE",
                "externalUniqueNumber": "1532103675510",
                "issuedAt": 1532103850,
                "qrCodeAsBase64": "iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAADqElEQVR42u3dQW7DMBAEQf3/08kLcgggame41UBugSGLLB8Wlvn8SPqzxy2QAJEAkQCRAJEAkQCRAJEAkQCRBIgEiASIBIgEiASIBIgEiASIBIgkQCRAJEAkQKQtQJ7nqfj77/W3/P+29QIEEEAAAQQQQAABBBBAAAEEEEAAefeGj43uXrqeGzbApvUCBBDrBQgg1gsQQAABBBBAAAEEEEDefYMtY9vTG34KVPt6AQIIIIAAAggggAACCCCAAAIIIIAA8uX1pL0OIIAAAggggAACCCCAAAIIIIAAAgggjUDSxrZTrwMIIIAAAggggAACCCCAAAIIIIAAshNI+/W0bwyP3AICiPUCBBDrBQgg1gsQQAABBBBAAHH8Qe//O/4AEEAAAcSGBwQQQAABBBBAAAEEkLuBbGvboZ9Xr6VbAIgAAQQQQAABBBBAAAEEEEAWAUkb97WPSacgn36/icABAQQQQAABBBBAAAEEEEAAAQSQTUCmNtKtxwe0jKONeQEBBBBAAAEEEEAAAQQQQAABBJA7xrxp48d24FMbO/FRWUAAAQQQQAABBBBAAAEEEEAAAQSQOSAtX2JMO7ag/XqcDwIIIIAAAggggAACCCCAAAIIIIBkPnKbNlZtOV4h7T7fMBYGBBBAAAEEEEAAAQQQQAABBBBANgFpH1e2f1Ccvs6WL5cCAggggAACCCCAAAIIIIAAAggggLy7YdIWtGX8e3qMPDWmXjvmBQQQQAABBBBAAAEEEEAAAQQQQD4G0n4cQMsPwbWPYQEBBBBAAAEEEEAAAQQQQAABBBBAMse8UzeqZew59YHT8ogxIIAAAggggAACCCCAAAIIIIAAAoiSF3RqzNvygQAIIIAAAggggAACCCCAAAIIIIAAMrugaV8aPL2gLWNVPxwHCCCAAAIIIIAAAggggAACCCCA3A2kZWybNg5tHzs37R9AAAEEEEAAAQQQQAABBBBAAAEEkPOPiKZtmLQxb/s4HRBAAAEEEEAAAQQQQAABBBBAAAEEkC+vJ25TlP8wHSCAAAIIIIAAAggggAACCCCAAAIIIAkL136Y6dT7AgQQQAABBBBAAAEEEEAAAQQQQACZXdBbx5i3bsimMTgggAACCCCAAAIIIIAAAggggAACSP9GuvUwzZb7CQgggAACCCCAAAIIIIAAAggggAAiCRAJEAkQCRAJEAkQCRAJEAkQCRBJgEiASIBIgEiASIBIgEiASIBIAkQCRAJEAkQCRErpF7hX1b0GLrAmAAAAAElFTkSuQmCC"
            }
        }';
        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(new Response(200, [], $mockResponse));

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Execute the transaction expecting it to raise TransactionCreateException
        // because the mock will make the HttpClient return Null
        $shoppingCart = ShoppingCartMocks::get();

        // This should raise a SignException
        try {
            $this->setExpectedException(SignException::class, 'The response signature is not valid');
            $response = Transaction::create($shoppingCart);
        }
        finally {
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }
    }

    public function testTransactionCreationWorksTakingKeysFromGetenv()
    {

        $originalApiKey = OnepayBase::getApiKey();
        $originalSharedSecret = OnepayBase::getSharedSecret();
        OnepayBase::setApiKey(null);
        OnepayBase::setSharedSecret(null);
        // Can't use getters, they will return something from getenv!
        // and we need to check if they are actually null in the static variable
        $OnepayBaseReflection = new \ReflectionClass(OnepayBase::class);
        $nullApiKey = $OnepayBaseReflection->getStaticPropertyValue('apiKey');
        $nullSharedSecret = $OnepayBaseReflection->getStaticPropertyValue('sharedSecret');
        $this->assertNull($nullApiKey);
        $this->assertNull($nullSharedSecret);

        putenv('ONEPAY_API_KEY=' . $originalApiKey);
        putenv('ONEPAY_SHARED_SECRET=' . $originalSharedSecret);

        $this->assertEquals(OnepayBase::getApiKey(), getenv('ONEPAY_API_KEY'));
        $this->assertEquals(OnepayBase::getSharedSecret(), getenv('ONEPAY_SHARED_SECRET'));

        $shoppingCart = ShoppingCartMocks::get();
        $response = Transaction::create($shoppingCart);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionCreationWorksWithoutOptions()
    {
        $shoppingCart = ShoppingCartMocks::get();
        $response = Transaction::create($shoppingCart);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionCreationWorksWithOptions()
    {
        $shoppingCart = new ShoppingCart();
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
                               "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart, $options);

        $this->assertEquals($response instanceof TransactionCreateResponse, true);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionCommitWorks()
    {
        // Setting commerce data
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
                               "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");

        // commit transaction
        $response = Transaction::commit(
                                        self::OCC_TO_COMMIT_TRANSACTION_TEST,
                                        self::EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST,
                                        $options
                                       );
        $this->assertEquals($response instanceof TransactionCommitResponse, true);
        $this->assertEquals($response->getResponseCode(), 'OK');
        $this->assertEquals($response->getDescription(), 'OK');

    }

    public function testTransactionCommitWorksWithoutOptions()
    {
        // commit transaction
        $response = Transaction::commit(
                                        self::OCC_TO_COMMIT_TRANSACTION_TEST,
                                        self::EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST
                                       );
        $this->assertEquals($response instanceof TransactionCommitResponse, true);
        $this->assertEquals($response->getResponseCode(), 'OK');
        $this->assertEquals($response->getDescription(), 'OK');

    }

    public function testTransactionCommitRaisesWhenResponseIsNull()
    {
        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(null);

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Setting commerce data
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
                               "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");

        // commit transaction

        // This should raise a TransactionCommitException
        try {
            $this->setExpectedException(TransactionCommitException::class, 'Could not obtain a response from the service');
            $response = Transaction::commit(
                self::OCC_TO_COMMIT_TRANSACTION_TEST,
                self::EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST,
                $options
               );
        }
        finally {
            // Reset the HttpClient static property to its original state
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }

    }

    public function testTransactionCommitRaisesWhenResponseIsNotOk()
    {
        $mockResponse = json_encode(array('responseCode' => 'INVALID_PARAMS',
                                          'description' => 'Parametros invalidos',
                                          'result' => null));
        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(new Response(200, [], $mockResponse));

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Setting commerce data
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
                               "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");

        // commit transaction

        // This should raise a TransactionCommitException
        try {
            $this->setExpectedException(TransactionCommitException::class, 'INVALID_PARAMS : Parametros invalidos');
            $response = Transaction::commit(
                self::OCC_TO_COMMIT_TRANSACTION_TEST,
                self::EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST,
                $options
               );
        }
        finally {
            // Reset the HttpClient static property to its original state
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }

    }

    public function testTransactionCommitRaisesWhenResponseSignatureIsNotValid()
    {
        $mockResponse = '{
            "responseCode": "OK",
            "description": "OK",
            "result": {
                "occ": "1807419329781765",
                "authorizationCode": "906637",
                "issuedAt": 1530822491,
                "signature": "INVALID SIGNATURE",
                "amount": 2490,
                "transactionDesc": "Venta Normal: Sin cuotas",
                "installmentsAmount": 2490,
                "installmentsNumber": 1,
                "buyOrder": "20180705161636514"
            }
        }';

        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(new Response(200, [], $mockResponse));

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Setting commerce data
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
                               "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");

        // commit transaction

        // This should raise a SignException
        try {
            $this->setExpectedException(SignException::class, 'The response signature is not valid');
            $response = Transaction::commit(
                self::OCC_TO_COMMIT_TRANSACTION_TEST,
                self::EXTERNAL_UNIQUE_NUMBER_TO_COMMIT_TRANSACTION_TEST,
                $options
               );
        }
        finally {
            // Reset the HttpClient static property to its original state
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }
    }

    public function testTransactionFailsWhenChannelMobileAndCallbackUrlNull() {

        OnepayBase::setCallbackUrl(null);
        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(null);

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Execute the transaction expecting it to raise TransactionCreateException
        // because the mock will make the HttpClient return Null
        $shoppingCart = ShoppingCartMocks::get();

        // This should raise a TransactionCreateException
        try {
            $this->setExpectedException(TransactionCreateException::class, 'You need to set a valid callback if you want to use the MOBILE channel');
            $response = Transaction::create($shoppingCart, ChannelEnum::MOBILE());
        }
        finally {
            // Reset the HttpClient static property to its original state
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }
    }

    public function testTransactionWhenChannelMobileAndCallbackUrlNotNull() {

        OnepayBase::setCallbackUrl("http://some.callback.url");
        $shoppingCart = new ShoppingCart();
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
            "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart, ChannelEnum::MOBILE());

        $this->assertEquals($response instanceof TransactionCreateResponse, true);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionFailsWhenChannelAPPAndAppSchemeNull() {
        // Create a mock http client that will return Null
        $httpClientStub = $this->getMock(HttpClient::class, array('post'));
        $httpClientStub->expects($this->any())->method('post')->willReturn(null);

        // Alter the private static property of Transaction 'httpClient'
        // to be the httpClientStub
        $reflectedClass = new \ReflectionClass(Transaction::class);
        $reflectedHttpClient = $reflectedClass->getProperty('httpClient');
        $reflectedHttpClient->setAccessible(true);
        $reflectedHttpClient->setValue($httpClientStub);

        // Execute the transaction expecting it to raise TransactionCreateException
        // because the mock will make the HttpClient return Null
        $shoppingCart = ShoppingCartMocks::get();

        // This should raise a TransactionCreateException
        try {
            $this->setExpectedException(TransactionCreateException::class, 'You need to set an appScheme if you want to use the APP channel');
            $response = Transaction::create($shoppingCart, ChannelEnum::APP());
        }
        finally {
            // Reset the HttpClient static property to its original state
            $reflectedHttpClient->setValue(null);
            $reflectedHttpClient->setAccessible(false);
        }
    }

    public function testTransactionWhenChannelAPPAndAppSchemeNotNull() {
        OnepayBase::setAppScheme('somescheme');
        $shoppingCart = new ShoppingCart();
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
            "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart, ChannelEnum::APP());

        $this->assertEquals($response instanceof TransactionCreateResponse, true);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionWhenExternalUniqueNumberNull() {
        OnepayBase::setAppScheme('somescheme');
        $shoppingCart = new ShoppingCart();
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
            "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart, ChannelEnum::APP(), null);

        $this->assertEquals($response instanceof TransactionCreateResponse, true);
        $this->assertEquals($response->getResponseCode(), "OK");
        $this->assertEquals($response->getDescription(), "OK");
        $this->assertNotNull($response->getQrCodeAsBase64());
    }

    public function testTransactionWhenExternalUniqueNumberPresent() {
        OnepayBase::setAppScheme('somescheme');
        $shoppingCart = new ShoppingCart();
        $options = new Options("mUc0GxYGor6X8u-_oB3e-HWJulRG01WoC96-_tUA3Bg",
            "P4DCPS55QB2QLT56SQH6#W#LV76IAPYX");
        $firstItem = new Item("Zapatos", 1, 15000, null, -1);
        $secondItem = new Item("Pantalon", 1, 12500, null, -1);

        $shoppingCart->add($firstItem);
        $shoppingCart->add($secondItem);

        $this->assertEquals('Zapatos', $firstItem->getDescription());
        $this->assertEquals('Pantalon', $secondItem->getDescription());

        $response = Transaction::create($shoppingCart, ChannelEnum::APP(), "ABC123");

        $this->assertEquals(true, $response instanceof TransactionCreateResponse);
        $this->assertEquals("OK", $response->getResponseCode());
        $this->assertEquals("OK", $response->getDescription());
        $this->assertNotNull($response->getQrCodeAsBase64());
        $this->assertEquals("f506a955-800c-4185-8818-4ef9fca97aae", $response->getExternalUniqueNumber());
    }

    public function testTransactionIncludesWidthHeight()
    {

    }

}
