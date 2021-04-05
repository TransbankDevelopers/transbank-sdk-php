<?php

namespace Transbank\Webpay;

use Transbank\Utils\EnvironmentManager;
use Transbank\Utils\HttpClient;
use Transbank\Webpay\WebpayPlus\MallTransaction;
use Transbank\Webpay\WebpayPlus\Transaction;

/**
 * Class WebpayPlus.
 */
class WebpayPlus extends EnvironmentManager
{
    const DEFAULT_COMMERCE_CODE = '597055555532';
    const DEFAULT_DEFERRED_COMMERCE_CODE = '597055555540';

    const DEFAULT_MALL_COMMERCE_CODE = '597055555535';
    const DEFAULT_MALL_CHILD_COMMERCE_CODE_1 = '597055555536';
    const DEFAULT_MALL_CHILD_COMMERCE_CODE_2 = '597055555537';

    const DEFAULT_MALL_DEFERRED_COMMERCE_CODE = '597055555581';
    const DEFAULT_MALL_DEFERRED_CHILD_COMMERCE_CODE_1 = '597055555582';
    const DEFAULT_MALL_DEFERRED_CHILD_COMMERCE_CODE_2 = '597055555583';

    const DEFAULT_API_KEY = Options::DEFAULT_API_KEY;

    protected static $globalOptions = null;

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
     * @param Options|null    $options
     * @param HttpClient|null $httpClient
     *
     * @return Transaction
     */
    public static function transaction(Options $options = null, HttpClient $httpClient = null)
    {
        return new self::$transactionClass($options, $httpClient);
    }

    /**
     * @param Options|null    $options
     * @param HttpClient|null $httpClient
     *
     * @return MallTransaction
     */
    public static function mallTransaction(Options $options = null, HttpClient $httpClient = null)
    {
        return new self::$mallTransactionClass($options, $httpClient);
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

    public static function configureForTesting()
    {
        self::configureForIntegration(static::DEFAULT_COMMERCE_CODE);
    }

    public static function configureForTestingDeferred()
    {
        self::configureForIntegration(static::DEFAULT_DEFERRED_COMMERCE_CODE);
    }

    public static function configureForTestingMall()
    {
        self::configureForIntegration(static::DEFAULT_MALL_COMMERCE_CODE);
    }

    public static function configureForTestingMallDeferred()
    {
        self::configureForIntegration(static::DEFAULT_MALL_DEFERRED_COMMERCE_CODE);
    }
}
