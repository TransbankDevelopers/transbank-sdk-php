<?php


namespace Transbank\Patpass;

/**
 * Class Options
 *
 * @package Transbank\Webpay
 */
class Options extends \Transbank\Webpay\Options
{
    /**
     * Default API key (which is sent as a header when making requests to Transbank
     * on a field called "Tbk-Api-Key-Secret")
     */
    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';
    const DEFAULT_INTEGRATION_TYPE = "TEST";
    const DEFAULT_PATPASS_BY_WEBPAY_COMMERCE_CODE = '597055555550';
    const DEFAULT_PATPASS_COMERCIO_API_KEY = 'cxxXQgGD9vrVe4M41FIt';
    const DEFAULT_PATPASS_COMERCIO_COMMERCE_CODE = '28299257';

    /**
     * @return Options Return an instance of Options with default values
     * configured
     */
    public static function defaultConfig()
    {
        return new Options(
            self::DEFAULT_API_KEY,
            self::DEFAULT_PATPASS_BY_WEBPAY_COMMERCE_CODE
        );
    }
    
}
