<?php

namespace Transbank\Webpay;

use Transbank\Contracts\EnvironmentManager;
use Transbank\Utils\ConfiguresEnvironment;
use Transbank\Utils\HttpClient;
use Transbank\Webpay\WebpayPlus\MallTransaction;
use Transbank\Webpay\WebpayPlus\Transaction;

/**
 * Class WebpayPlus.
 */
class WebpayPlus implements EnvironmentManager
{
    use ConfiguresEnvironment;
    
    const DEFAULT_WEBPAY_PLUS_COMMERCE_CODE = '597055555532';
    
    /*
    |--------------------------------------------------------------------------
    | Public Facade
    |--------------------------------------------------------------------------
    */
    /**
     * @var string
     */
    protected static $transactionClass = Transaction::class;
    /**
     * @var string
     */
    protected static $mallTransactionClass = MallTransaction::class;
    
    /**
     * @param Options|null $options
     * @param HttpClient|null $httpClient
     * @return Transaction
     */
    public static function transaction(Options $options = null, HttpClient $httpClient = null)
    {
        return (new self::$transactionClass($options, $httpClient));
    }
    
    /**
     * @param Options|null $options
     * @param HttpClient|null $httpClient
     * @return MallTransaction
     */
    public static function mallTransaction(Options $options = null, HttpClient $httpClient = null)
    {
        return (new self::$mallTransactionClass($options, $httpClient));
    }
    
    /**
     * @param string $transactionClass
     */
    public static function setTransactionClass($transactionClass)
    {
        self::$transactionClass = $transactionClass;
    }
    
    /**
     * @param string $mallTransactionClass
     */
    public static function setMallTransactionClass($mallTransactionClass)
    {
        self::$mallTransactionClass = $mallTransactionClass;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Environment Configuration
    |--------------------------------------------------------------------------
    */
    
    /**
     * @var string
     */
    private static $apiKey = Options::DEFAULT_API_KEY;
    /**
     * @var string
     */
    private static $commerceCode = Options::DEFAULT_COMMERCE_CODE;
    /**
     * @var string
     */
    private static $integrationType = Options::DEFAULT_INTEGRATION_TYPE;
    
   
    public static function configureForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_COMMERCE_CODE);
        self::setIntegrationType(Options::ENVIRONMENT_INTEGRATION);
    }
    
    public static function configureMallForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_WEBPAY_PLUS_MALL_COMMERCE_CODE);
        self::setIntegrationType(Options::ENVIRONMENT_INTEGRATION);
    }
    
    public static function configureMallDeferredForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_WEBPAY_PLUS_MALL_DEFERRED_COMMERCE_CODE);
        self::setIntegrationType(Options::ENVIRONMENT_INTEGRATION);
    }
    
    public static function configureDeferredForTesting()
    {
        self::setApiKey(Options::DEFAULT_API_KEY);
        self::setCommerceCode(Options::DEFAULT_DEFERRED_COMMERCE_CODE);
        self::setIntegrationType(Options::ENVIRONMENT_INTEGRATION);
    }
    
    /**
     * Get the default options if none are given.
     *
     * @return Options
     */
    public function getDefaultOptions()
    {
        return Options::forIntegration(Options::DEFAULT_API_KEY, static::DEFAULT_WEBPAY_PLUS_COMMERCE_CODE);
    }
    
}
