<?php

namespace Transbank\Webpay;

use Transbank\Webpay\WebpayPlus\Transaction;

class WebpayPlusTest extends \PHPUnit_Framework_TestCase
{
    protected function setup()
    {
        $this->amount = 1000;
        $this->sessionId = "some_session_id";
        $this->buyOrder = "123999555";
        $this->returnUrl = "https://comercio.cl/callbacks/transaccion_creada_exitosamente";
    }

    public function testCreateATransactionWithoutOptions()
    {
        $transactionResult = WebpayPlus\Transaction::create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl
        );

        $this->assertNotNull($transactionResult->getToken());
        $this->assertNotNull($transactionResult->getUrl());
    }

    public function testCreateATransactionWithOptions()
    {
        $options = new Options('579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C', '597055555532');
        $transactionResult = WebpayPlus\Transaction::create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl,
            $options
        );

        $this->assertNotNull($transactionResult->getToken());
        $this->assertNotNull($transactionResult->getUrl());
    }

    public function testCreateTransactionWithIncorrectCredentialsShouldFail()
    {
        $options = new Options(
            'fakeApiKey',
            'fakeCommerceCode'
        );

        $this->setExpectedException(\Exception::class, 'Not Authorized');
        WebpayPlus\Transaction::create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl,
            $options
        );
    }
}
