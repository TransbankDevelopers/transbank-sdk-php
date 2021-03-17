<?php

namespace Transbank\TransaccionCompleta;

class Options extends \Transbank\Webpay\Options
{
    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
    const DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE = '597055555530';
    const DEFAULT_TRANSACCION_COMPLETA_MALL_COMMERCE_CODE = '597055555551';
    const DEFAULT_TRANSACCION_COMPLETA_MALL_CHILD_COMMERCE_CODE = ['597055555552', '597055555553'];
    const DEFAULT_INTEGRATION_TYPE = 'TEST';

    public $apiKey = null;
    public $commerceCode = null;
    public $integrationType = 'TEST';

    public function __construct($apiKey, $commerceCode, $integrationType = 'TEST')
    {
        $this->setApiKey($apiKey);
        $this->setCommerceCode($commerceCode);
        $this->setIntegrationType($integrationType);
    }

    public static function defaultConfig()
    {
        return new Options(self::DEFAULT_API_KEY, self::DEFAULT_TRANSACCION_COMPLETA_COMMERCE_CODE);
    }
}
