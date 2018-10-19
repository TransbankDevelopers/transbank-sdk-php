<?php
/**
 * Created by PhpStorm.
 * User: goncafa
 * Date: 13-09-18
 * Time: 17:00
 */

namespace Transbank\Webpay;


use PHPUnit\Framework\TestCase;

final class WebpayTest extends TestCase
{
    public function setup()
    {
        $configuration = new Configuration();
    }

    public function testWebpay()
    {
        echo "\n";
        echo '===========================================================================';
        echo "\n";
        echo "\n";

        $transaction = (new Webpay(Configuration::forTestingWebpayPlusNormal()))->getNormalTransaction();

        $amount = 1000;
        $session_id = 'mi-id-de-sesion1234';
        $buy_order = strval(rand(100000, 999999999));
        $return_url = 'https://callback/resultado/de/transaccion';
        $final_url = 'https://callback/final/post/comprobante/webpay';

        $init_result = $transaction->initTransaction(
            $amount, $buy_order, $session_id, $return_url, $final_url);

        foreach ($init_result as $k => $v) {
            echo $k . ' = [' . $v . '],' . "\n";
        }

        echo "\n";
        echo "\n";
        echo '===========================================================================';
        echo "\n";
    }
}
