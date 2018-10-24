<?php
namespace Transbank\Webpay;

use PHPUnit\Framework\TestCase;

final class WebpayTest extends TestCase
{
    public function testWebpayNormal()
    {
        $transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();

        $amount = 1000;
        $session_id = 'mi-id-de-sesion1234';
        $buy_order = strval(rand(100000, 999999999));
        $return_url = 'https://callback/resultado/de/transaccion';
        $final_url = 'https://callback/final/post/comprobante/webpay';

        $init_result = $transaction->initTransaction(
            $amount, $buy_order, $session_id, $return_url, $final_url);

        $this->assertNotNull($init_result->token, '$init_result->token Can not be null');
        $this->assertNotNull($init_result->url, '$init_result->url Can not be null');
    }

    public function testWebpayMall()
    {
        $transaction = (new Webpay(Configuration::forTestingWebpayPlusMall()))->getMallNormalTransaction();

        $amount = 1000;
        $session_id = 'mi-id-de-sesion1234';
        $buy_order = strval(rand(100000, 999999999));
        $return_url = 'https://callback/resultado/de/transaccion';
        $final_url = 'https://callback/final/post/comprobante/webpay';

        $transactions = array();
        $transactions[] = array(
            "storeCode" => 597044444402,
            "amount" => $amount,
            "buyOrder" => strval(rand(100000, 999999999)),
            "sessionId" => $session_id
        );
        $transactions[] = array(
            "storeCode" => 597044444403,
            "amount" => $amount,
            "buyOrder" => strval(rand(100000, 999999999)),
            "sessionId" => $session_id
        );

        $init_result = $transaction->initTransaction(
            $buy_order, $session_id, $return_url, $final_url, $transactions);

        $this->assertNotNull($init_result->token, '$init_result->token Can not be null');
        $this->assertNotNull($init_result->url, '$init_result->url Can not be null');
    }

    public function testWebpayOneclick()
    {
        $transaction = (new Webpay(Configuration::forTestingWebpayOneClickNormal()))->getOneClickTransaction();

        $return_url = 'https://callback/resultado/de/transaccion';

        /** Nombre de usuario o cliente en el sistema del comercio */
        $username = "username";

        /** Dirección de correo electrónico registrada por el comercio */
        $email = "username@allware.cl";

        $init_result = $transaction->initInscription($username, $email, $return_url);

        $this->assertNotNull($init_result->token, '$init_result->token Can not be null');
        $this->assertNotNull($init_result->urlWebpay, '$init_result->urlWebpay Can not be null');
    }
}
