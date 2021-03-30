<?php

namespace Transbank\Sdk;

use Closure;
use LogicException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionFunction;
use RuntimeException;
use Transbank\Sdk\Credentials\Container;
use Transbank\Sdk\Events\NullDispatcher;
use Transbank\Sdk\Http\Connector;

class Transbank
{
    /**
     * Current SDK version.
     *
     * @var string
     */
    public const VERSION = '2.0';

    /**
     * Callback that constructs a Transbank instance.
     *
     * @var null|Closure(): Transbank
     */
    protected static $builder = null;

    /**
     * Transbank instance singleton helper.
     *
     * @var Transbank|null
     */
    protected static $singleton = null;
    /**
     * Logger instance.
     *
     * @var \Psr\Log\LoggerInterface
     */
    public $logger;
    /**
     * Event dispatcher instance.
     *
     * @var \Psr\EventDispatcher\EventDispatcherInterface
     */
    public $event;
    /**
     * HTTP Connector to prepare and communicate to Transbank Servers.
     *
     * @var \Transbank\Sdk\Http\Connector
     */
    public $connector;
    /**
     * Credentials container.
     *
     * @var \Transbank\Sdk\Credentials\Container
     */
    protected $credentials;
    /**
     * The current environment for all Transbank services.
     *
     * @var bool
     */
    protected $production = false;

    /**
     * Webpay service instance.
     *
     * @var \Transbank\Sdk\Services\Webpay
     */
    protected $webpay;

    /**
     * Webpay Mall service instance.
     *
     * @var \Transbank\Sdk\Services\WebpayMall
     */
    protected $webpayMall;

    /**
     * Oneclick Mall service instance.
     *
     * @var \Transbank\Sdk\Services\OneclickMall
     */
    private $oneclickMall;

    /**
     * Transbank constructor.
     *
     * @param  \Transbank\Sdk\Credentials\Container  $credentials
     * @param  \Psr\Log\LoggerInterface  $logger
     * @param  \Psr\EventDispatcher\EventDispatcherInterface  $event
     * @param  \Transbank\Sdk\Http\Connector  $connector
     */
    public function __construct(
        Container $credentials,
        LoggerInterface $logger,
        EventDispatcherInterface $event,
        Connector $connector
    ) {
        $this->connector = $connector;
        $this->event = $event;
        $this->logger = $logger;
        $this->credentials = $credentials;
    }

    /**
     * Creates a new Transbank instance using Guzzle as HTTP Client.
     *
     * @return static
     * @codeCoverageIgnore
     */
    public static function make(): Transbank
    {
        $client = null;

        // Get one of the two clients HTTP Clients and try to use them if they're installed.
        switch (true) {
            case class_exists(\GuzzleHttp\Client::class):
                $client = new \GuzzleHttp\Client();
                break;
            case class_exists(\Symfony\Component\HttpClient\Psr18Client::class):
                $client = new \Symfony\Component\HttpClient\Psr18Client();
                break;
            default:
                throw new RuntimeException(
                    'The "guzzlehttp/guzzle" or "symfony/http-client" libraries are not present. Install one or use your own PSR-18 HTTP Client.'
                );
        }

        return new static(
            new Container(),
            new NullLogger(),
            new NullDispatcher(),
            new Connector($client, $factory = new Psr17Factory(), $factory)
        );
    }

    /**
     * Registers a callback that returns a Transbank instance.
     *
     * @param  Closure(): Transbank  $constructor
     *
     * @return void
     * @throws \ReflectionException
     */
    public static function singletonBuilder(Closure $constructor): void
    {
        $return = (new ReflectionFunction($constructor))->getReturnType();

        if (!$return || $return->getName() !== __CLASS__) {
            throw new LogicException('Closure must declare returning a Transbank object instance.');
        }

        static::$builder = $constructor;
    }

    /**
     * Returns a Transbank instance as a singleton.
     *
     * @param  mixed  ...$arguments
     *
     * @return Transbank
     */
    public static function singleton(...$arguments): Transbank
    {
        if (static::$singleton) {
            return static::$singleton;
        }

        if (!static::$builder) {
            throw new RuntimeException('There is no constructor to create a Transbank instance.');
        }

        return static::$singleton = call_user_func(static::$builder, ...$arguments);
    }

    /**
     * Sets all the Transbank services to run in production servers.
     *
     * Supported services:
     *      - webpay
     *      - webpayMall
     *      - oneclickMall
     *      - fullTransaction
     *      - fullTransactionMall
     *
     * @param  array<array<string,string>>  $credentials
     *
     * @return $this
     */
    public function toProduction(array $credentials): Transbank
    {
        if (empty($credentials)) {
            throw new LogicException('Cannot set empty credentials for production environment.');
        }

        $this->credentials->setFromArray($credentials);

        $this->production = true;

        $this->logger->debug(
            'Transbank has been set to production environment.',
            ['credentials' => array_keys($credentials)]
        );

        return $this;
    }

    /**
     * Returns the SDK to integration environment.
     *
     * @return $this
     */
    public function toIntegration(): Transbank
    {
        $this->production = false;

        $this->logger->debug('Transbank has been set to integration environment.');

        return $this;
    }

    /**
     * Check if the current Transbank SDK are running in integration environment.
     *
     * @return bool
     */
    public function isIntegration(): bool
    {
        return !$this->isProduction();
    }

    /**
     * Check if the current Transbank SDK are running in production environment.
     *
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->production;
    }

    /**
     * Returns the Webpay service.
     *
     * @return \Transbank\Sdk\Services\Webpay
     */
    public function webpay(): Services\Webpay
    {
        return $this->webpay ?? $this->webpay = new Services\Webpay($this, $this->credentials);
    }

    /**
     * Returns the Webpay Mall service.
     *
     * @return \Transbank\Sdk\Services\WebpayMall
     */
    public function webpayMall(): Services\WebpayMall
    {
        return $this->webpayMall ?? $this->webpayMall = new Services\WebpayMall($this, $this->credentials);
    }

    /**
     * Returns the Oneclick Mall service.
     *
     * @return \Transbank\Sdk\Services\OneclickMall
     */
    public function oneclickMall(): Services\OneclickMall
    {
        return $this->oneclickMall ?? $this->oneclickMall = new Services\OneclickMall($this, $this->credentials);
    }
}
