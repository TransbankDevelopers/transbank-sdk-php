<?php


namespace Transbank\Webpay\WebpayPlus;


class Configuration extends \Transbank\Configuration
{


    public function __construct()
    {

    }

    public static function defaultConfig()
    {
        return new Configuration();
    }

}
