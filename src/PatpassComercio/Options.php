<?php

namespace Transbank\PatpassComercio;

use Transbank\Webpay\Options as WebpayOptions;

/**
 * Class Options.
 */
class Options extends WebpayOptions
{
    const BASE_URL_PRODUCTION = 'https://www.pagoautomaticocontarjetas.cl/';
    const BASE_URL_INTEGRATION = 'https://pagoautomaticocontarjetasint.transbank.cl/';

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'commercecode'     => $this->getCommerceCode(),
            'Authorization'    => $this->getApiKey(),
        ];
    }
}
