<?php

namespace Tests\Credentials;

use Error;
use LogicException;
use RuntimeException;
use Transbank\Sdk\Credentials\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    protected Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function test_set_all_from_array(): void
    {
        $credentials = ['key' => 'test_key', 'secret' => 'test_secret'];

        $this->container->setFromArray([
            'webpay' => $credentials,
            'webpayMall' => $credentials,
            'oneclickMall' => $credentials,
            'fullTransaction' => $credentials,
        ]);

        foreach (['webpay', 'webpayMall', 'oneclickMall', 'fullTransaction'] as $service) {
            $credential = $this->container->getProductionCredentials($service);
            static::assertEquals($credentials['key'], $credential->key);
            static::assertEquals($credentials['secret'], $credential->secret);
        }
    }

    public function test_exception_when_service_doesnt_exists(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The Transbank service [invalid_service] doesn\'t exist for these credentials.');

        $this->container->setFromArray([
            'invalid_service' => ['key' => 'test_key', 'secret' => 'test_secret']
        ]);
    }

    public function test_exception_when_service_key_is_empty(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Credentials for [webpay] must have a [key] and [secret].');

        $this->container->setFromArray([
            'webpay' => ['secret' => 'test_secret']
        ]);
    }

    public function test_exception_when_service_secret_is_empty(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Credentials for [webpay] must have a [key] and [secret].');

        $this->container->setFromArray([
            'webpay' => ['key' => 'test_key']
        ]);
    }

    public function test_exception_when_service_has_no_credentials(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Production credentials for [webpayMall] are not set.');

        $this->container->setFromArray([
            'webpay' => ['secret' => 'test_secret', 'key' => 'test_key']
        ]);

        $this->container->getProductionCredentials('webpayMall');
    }
}
