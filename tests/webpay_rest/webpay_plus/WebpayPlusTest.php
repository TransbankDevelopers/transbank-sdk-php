<?php

namespace Transbank\Webpay;


class WebpayPlusTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateATransactionWithoutOptions()
    {
        $amount = 1000;
        $sessionId = "some_session_id";
        $buyOrder = "123999555";
        $returnUrl = "https://comercio.cl/callbacks/transaccion_creada_exitosamente";


        $transactionResult = WebpayPlus::create($buyOrder,
                                                $sessionId,
                                                $amount,
                                                $returnUrl);

        $this->assertNotNull($transactionResult->getToken());
        $this->assertNotNull($transactionResult->getUrl());


//        $transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();

//        $amount = 1000;
//        $session_id = 'mi-id-de-sesion1234';
//        $buy_order = strval(rand(100000, 999999999));
//        $return_url = 'https://callback/resultado/de/transaccion';
//        $final_url = 'https://callback/final/post/comprobante/webpay';
//
//        $init_result = $transaction->initTransaction(
//            $amount, $buy_order, $session_id, $return_url, $final_url);
//
//        $this->assertNotNull($init_result->token, '$init_result->token Can not be null');
//        $this->assertNotNull($init_result->url, '$init_result->url Can not be null');
//

    }
}
