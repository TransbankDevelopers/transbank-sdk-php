<?php

namespace Transbank\Patpass;

/**
 * Class Options.
 */
class Options extends \Transbank\Webpay\Options
{
    const BASE_URL_PRODUCTION = 'https://www.pagoautomaticocontarjetas.cl/';
    const BASE_URL_INTEGRATION = 'https://pagoautomaticocontarjetasint.transbank.cl/';

    /**
     * @return array
     */
    public function getHeaders()
    {
        return [
            'commercecode'     => $this->getCommerceCode(),
            'Authorization'    => $this->getApiKey(),
        ];
    }
}
