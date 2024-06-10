<?php

namespace Transbank\Webpay;

use Transbank\Contracts\RequestService;
use Transbank\Webpay\WebpayPlus\MallTransaction;
use Transbank\Webpay\WebpayPlus\Transaction;

/**
 * Class WebpayPlus.
 */
class WebpayPlus
{
    const INTEGRATION_COMMERCE_CODE = '597055555532';
    const INTEGRATION_DEFERRED_COMMERCE_CODE = '597055555540';

    const INTEGRATION_MALL_COMMERCE_CODE = '597055555535';
    const INTEGRATION_MALL_CHILD_COMMERCE_CODE_1 = '597055555536';
    const INTEGRATION_MALL_CHILD_COMMERCE_CODE_2 = '597055555537';

    const INTEGRATION_MALL_DEFERRED_COMMERCE_CODE = '597055555581';
    const INTEGRATION_MALL_DEFERRED_CHILD_COMMERCE_CODE_1 = '597055555582';
    const INTEGRATION_MALL_DEFERRED_CHILD_COMMERCE_CODE_2 = '597055555583';

    const INTEGRATION_API_KEY = Options::INTEGRATION_API_KEY;

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
    public static function transaction(Options $options, RequestService $requestService = null)
    {
        return new static::$transactionClass($options, $requestService);
    }

    /**
     * @param Options|null        $options
     * @param RequestService|null $requestService
     *
     * @return MallTransaction
     */
    public static function mallTransaction(Options $options, RequestService $requestService = null)
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
}
