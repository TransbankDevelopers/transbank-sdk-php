<?php

namespace Transbank\Onepay;
use PHPUnit\Framework\TestCase;
require_once(__DIR__ . '/mocks/ShoppingCartMocks.php');

class TransactionCreateRequestTest extends TestCase
{
    public $optionsWithApiKey;
    public $optionsWithSharedSecret;
    public $builder;
    public $emptyOptions;
    public $optionsWithDefaultValues;
    public $optionsWithQrWidthHeight;
    public $optionsWithCommerceLogoUrl;
    public $optionsWithQrWidthHeightAndCommerceLogoUrl;

    protected function setup()
    {
        $this->emptyOptions = new Options();
        $this->optionsWithApiKey = new Options("someapikey");
        $this->optionsWithDefaultValues = new Options(null, null);
        $this->optionsWithQrWidthHeight = new Options(null, null, 150);
        $this->optionsWithCommerceLogoUrl = new Options(null, null, null, "http://falseaddress.cl/image.jpg");
        $this->optionsWithQrWidthHeightAndCommerceLogoUrl = new Options(null, null, 180, "http://fakeaddr.cl/imagen.jpg");
        $this->shoppingCart = ShoppingCartMocks::get();
        $this->builder = OnepayRequestBuilder::getInstance();
    }

    public function testTransactionCreateRequestWithApiKey()
    {
        $transactionCreateRequest = $this->builder->buildCreateRequest($this->shoppingCart, 'WEB', '1231245', $this->optionsWithApiKey);

        $this->assertTrue($transactionCreateRequest instanceof TransactionCreateRequest);
        $this->assertEquals( "someapikey", $transactionCreateRequest->getApiKey());
    }


    public function testTransactionCreateRequestCreatedWithEmptyOptions()
    {
        $transactionCreateRequest = $this->builder->buildCreateRequest($this->shoppingCart, 'WEB', '1231245', $this->emptyOptions);

        $this->assertTrue($transactionCreateRequest instanceof TransactionCreateRequest);
        $this->assertEquals( OnepayBase::getQrWidthHeight(), $transactionCreateRequest->getWidthHeight());
        $this->assertEquals(OnepayBase::getCommerceLogoUrl(), $transactionCreateRequest->getCommerceLogoUrl());
    }

    public function testTransactionCreateRequestCreatedWithDefaultValues()
    {
        $transactionCreateRequest = $this->builder->buildCreateRequest($this->shoppingCart, 'WEB', '1231245', $this->optionsWithDefaultValues);

        $this->assertTrue($transactionCreateRequest instanceof TransactionCreateRequest);
        $this->assertEquals(OnepayBase::getQrWidthHeight(), $transactionCreateRequest->getWidthHeight());
        $this->assertEquals(OnepayBase::getCommerceLogoUrl(), $transactionCreateRequest->getCommerceLogoUrl());
    }


    public function testTransactionCreateRequestCreatedWithQrWidthHeight()
    {
        $transactionCreateRequest = $this->builder->buildCreateRequest($this->shoppingCart, 'WEB', '1231245', $this->optionsWithQrWidthHeight);

        $this->assertTrue($transactionCreateRequest instanceof TransactionCreateRequest);
        $this->assertEquals(150, $transactionCreateRequest->getWidthHeight());
        $this->assertEquals(OnepayBase::getCommerceLogoUrl(), $transactionCreateRequest->getCommerceLogoUrl());
    }

    public function testTransactionCreateRequestCreatedWithCommerceLogoUrl()
    {
        $transactionCreateRequest = $this->builder->buildCreateRequest($this->shoppingCart, 'WEB', '1231245', $this->optionsWithCommerceLogoUrl);

        $this->assertTrue($transactionCreateRequest instanceof TransactionCreateRequest);
        $this->assertEquals(OnepayBase::getQrWidthHeight(), $transactionCreateRequest->getWidthHeight());
        $this->assertEquals("http://falseaddress.cl/image.jpg", $transactionCreateRequest->getCommerceLogoUrl());
    }

    public function testTransactionCreateRequestCreatedWithQrWidthHeightAndCommerceLogoUrl()
    {
        $transactionCreateRequest = $this->builder->buildCreateRequest($this->shoppingCart, 'WEB', '1231245', $this->optionsWithQrWidthHeightAndCommerceLogoUrl);

        $this->assertTrue($transactionCreateRequest instanceof TransactionCreateRequest);
        $this->assertEquals(180, $transactionCreateRequest->getWidthHeight());
        $this->assertEquals("http://fakeaddr.cl/imagen.jpg", $transactionCreateRequest->getCommerceLogoUrl());
    }

    public function testTransactionCreateRequestShouldRaiseIfWidthHeightIsAttemptedToBeSetAsNull()
    {
        $transactionCreateRequest = $this->builder->buildCreateRequest($this->shoppingCart, 'WEB', '1231245', $this->optionsWithQrWidthHeightAndCommerceLogoUrl);
        $this->setExpectedException(\Exception::class, 'WidthHeight cannot be null.');
        $transactionCreateRequest->setWidthHeight(null);
    }

    public function testTransactionCreateRequestHasNoWidthHeightWhenCreatedWithNullWidthHeight()
    {
        $externalUniqueNumber = 'somevalue';
        $total = $this->shoppingCart->getTotal();
        $itemsQuantity = $this->shoppingCart->getItemQuantity();
        $issuedAt = time();
        $items = $this->shoppingCart->getItems();
        $callbackUrl = "http://url.com";
        $channel = 'WEB';
        $appScheme = null;
        $widthHeight = null;
        $commerceLogoUrl = "http://logo.url";

        $tcr = new TransactionCreateRequest($externalUniqueNumber, $total, $itemsQuantity,
            $issuedAt,$items, $callbackUrl, $channel, $appScheme,
            $widthHeight, $commerceLogoUrl);
        // WidthHeight should not exist, so it is not serialized nor sent to TBK
        $widthHeightExists = array_key_exists('widthHeight', get_object_vars($tcr));
        $this->assertEquals($widthHeightExists, false);
    }

}
