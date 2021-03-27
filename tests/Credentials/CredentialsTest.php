<?php

namespace Tests\Credentials;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Transbank\Sdk\Credentials\Credentials;

class CredentialsTest extends TestCase
{
    public function test_returns_integration_credentials(): void
    {
        $services = [
            'webpay',
            'webpayMall',
            'webpayMall.capture',
            'oneclick',
            'oneclick.capture',
            'fullTransaction',
            'fullTransaction.capture',
            'fullTransactionMall',
            'fullTransactionMall.capture',
        ];

        foreach ($services as $service) {
            $credentials = Credentials::integrationCredentials($service);

            static::assertEquals(Credentials::INTEGRATION_KEYS[$service], $credentials->key);
            static::assertEquals(Credentials::INTEGRATION_SECRET, $credentials->secret);
        }
    }

    public function test_exception_if_integration_services_doesnt_exists(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The integration key for [invalid service] doesn\'t exist.');

        Credentials::integrationCredentials('invalid service');
    }
}
