<?php

namespace Transbank\Webpay;

/**
 * Class Options.
 */
class Options
{
    const ENVIRONMENT_PRODUCTION = 'LIVE';
    const ENVIRONMENT_INTEGRATION = 'TEST';
    
    const BASE_URL_PRODUCTION = 'https://webpay3g.transbank.cl/';
    const BASE_URL_INTEGRATION = 'https://webpay3gint.transbank.cl/';

    /**
     * Default API key (which is sent as a header when making requests to Transbank
     * on a field called "Tbk-Api-Key-Secret").
     */
    const DEFAULT_API_KEY = '579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C';

    const DEFAULT_COMMERCE_CODE = '597055555532';
    const DEFAULT_WEBPAY_MODAL_COMMERCE_CODE = '597055555584';
    const DEFAULT_INTEGRATION_TYPE = 'TEST';
    const DEFAULT_INTEGRATION_TYPE_URL = 'https://webpay3gint.transbank.cl/';
    const DEFAULT_WEBPAY_PLUS_MALL_COMMERCE_CODE = '597055555535';
    const DEFAULT_WEBPAY_PLUS_MALL_DEFERRED_COMMERCE_CODE = '597055555581';
    const DEFAULT_WEBPAY_PLUS_MALL_DEFERRED_CHILD_COMMERCE_CODES = ['597055555582', '597055555583'];
    const DEFAULT_WEBPAY_PLUS_MALL_CHILD_COMMERCE_CODES = ['597055555536', '597055555537'];
    const DEFAULT_DEFERRED_COMMERCE_CODE = '597055555540';

    const DEFAULT_ONECLICK_MALL_COMMERCE_CODE = '597055555541';
    const DEFAULT_ONECLICK_MALL_CHILD_COMMERCE_CODE_1 = '597055555542';
    const DEFAULT_ONECLICK_MALL_CHILD_COMMERCE_CODE_2 = '597055555543';

    const DEFAULT_ONECLICK_MALL_DEFERRED_COMMERCE_CODE = '597055555547';

    const DEFAULT_PATPASS_BY_WEBPAY_COMMERCE_CODE = '597055555550';

    /**
     * @var string Your api key, given by Transbank.Sent as a header when
     *             making requests to Transbank on a field called "Tbk-Api-Key-Secret"
     */
    public $apiKey = null;
    /**
     * @var string Your commerce code, given by Transbank. Sent as
     *             a header when making requests to Transbank on a field called "Tbk-Api-Key-Id"
     */
    public $commerceCode = null;
    /**
     * @var string Sets the environment that the SDK is going
     *             to point to (eg. TEST, LIVE, etc).
     */
    public $integrationType = self::ENVIRONMENT_INTEGRATION;

    public function __construct($apiKey, $commerceCode, $integrationType = self::ENVIRONMENT_INTEGRATION)
    {
        $this->setApiKey($apiKey);
        $this->setCommerceCode($commerceCode);
        $this->setIntegrationType($integrationType);
    }
    
    public static function forProduction($commerceCode, $apiKey)
    {
        return new static($apiKey, $commerceCode, self::ENVIRONMENT_PRODUCTION);
    }
    
    public static function forIntegration($commerceCode, $apiKey)
    {
        return new static($apiKey, $commerceCode, self::ENVIRONMENT_INTEGRATION);
    }
    
    public function isProduction()
    {
        return $this->getIntegrationType() === self::ENVIRONMENT_PRODUCTION;
    }

    /**
     * @return string
     */
    public function getIntegrationType()
    {
        return $this->integrationType;
    }

    /**
     * @param string $integrationType
     *
     * @return Options
     */
    public function setIntegrationType($integrationType)
    {
        $this->integrationType = $integrationType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return Options
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommerceCode()
    {
        return $this->commerceCode;
    }

    /**
     * @param mixed $commerceCode
     *
     * @return Options
     */
    public function setCommerceCode($commerceCode)
    {
        $this->commerceCode = $commerceCode;

        return $this;
    }

    /**
     * @return string Returns the base URL used for making requests, depending on which
     *                integration types
     */
    public function getApiBaseUrl()
    {
        if ($this->isProduction()) {
            return self::BASE_URL_PRODUCTION;
        }
        return self::BASE_URL_INTEGRATION;
    }
    
    /**
     *
     * @return array
     */
    public function getHeaders()
    {
        return [
            'Tbk-Api-Key-Id'     => $this->getCommerceCode(),
            'Tbk-Api-Key-Secret' => $this->getApiKey(),
        ];
    }
}
