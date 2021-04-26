<?php

namespace Transbank\Webpay;

use Transbank\Contracts\RequestService;
use Transbank\Utils\EnvironmentManager;
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
     * @param Options|null        $options
     * @param RequestService|null $requestService
     *
     * @return Transaction
     */
    public static function transaction(Options $options = null, RequestService $requestService = null)
    {
        return new static::$transactionClass($options, $requestService);
    }

    /**
     * @param Options|null        $options
     * @param RequestService|null $requestService
     *
     * @return MallTransaction
     */
    public static function mallTransaction(Options $options = null, RequestService $requestService = null)
    {
        return new static::$mallTransactionClass($options, $requestService);
    }

    /**
     * @param string $transactionClass
     */
    public static function setTransactionClass($transactionClass)
    {
        static::$transactionClass = $transactionClass;
    }

    /**
     * @param string $mallTransactionClass
     */
    public static function setMallTransactionClass($mallTransactionClass)
    {
        static::$mallTransactionClass = $mallTransactionClass;
    }

    /*
    |--------------------------------------------------------------------------
    | Environment Configuration
    |--------------------------------------------------------------------------
    */

    public static function configureForTesting()
    {
        static::configureForIntegration(static::DEFAULT_COMMERCE_CODE);
    }

    public static function configureForTestingDeferred()
    {
        static::configureForIntegration(static::DEFAULT_DEFERRED_COMMERCE_CODE);
    }

    public static function configureForTestingMall()
    {
        static::configureForIntegration(static::DEFAULT_MALL_COMMERCE_CODE);
    }

    public static function configureForTestingMallDeferred()
    {
        static::configureForIntegration(static::DEFAULT_MALL_DEFERRED_COMMERCE_CODE);
    }
}
