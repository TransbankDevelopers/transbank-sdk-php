<?php

use PHPUnit\Framework\TestCase;
use Transbank\PatpassComercio\Options;
use Transbank\PatpassComercio\Inscription;
use Transbank\PatpassComercio\Exceptions\InscriptionStartException;
use Transbank\PatpassComercio\Exceptions\InscriptionStatusException;
use Transbank\PatpassComercio\Responses\InscriptionStartResponse;
use Transbank\PatpassComercio\Responses\InscriptionStatusResponse;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;

class PatpassComercioTest extends TestCase
{
    /** @test */
    public function it_configures_with_options()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $options = new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION);
        $inscription = new Inscription($options);
        $inscriptionOptions = $inscription->getOptions();

        $this->assertSame($commerceCode, $inscriptionOptions->getCommerceCode());
        $this->assertSame($apiKey, $inscriptionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $inscriptionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $inscriptionOptions->getApiBaseUrl());
    }

    /** @test */
    public function it_configures_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $inscription = Inscription::buildForIntegration($apiKey, $commerceCode);
        $options = $inscription->getOptions();

        $this->assertSame($commerceCode, $options->getCommerceCode());
        $this->assertSame($apiKey, $options->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $options->getIntegrationType());
        $this->assertSame(Options::BASE_URL_INTEGRATION, $options->getApiBaseUrl());
    }

    /** @test */
    public function it_configures_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $inscription = Inscription::buildForProduction($apiKey, $commerceCode);
        $options = $inscription->getOptions();

        $this->assertSame($commerceCode, $options->getCommerceCode());
        $this->assertSame($apiKey, $options->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $options->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $options->getApiBaseUrl());
    }

    /** @test */
    public function it_returns_inscription_start_response()
    {
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_PRODUCTION);
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock->method('request')
            ->willReturn(
                [
                    'token' => 'fakeTokenResponse',
                    'url' => 'http://fakeurl.cl'
                ]
            );
        $inscription = new Inscription($options, $requestServiceMock);
        $start = $inscription->start(
            'https://www.url.cl',
            'Juanito',
            'Perez',
            'Perez',
            '11111111-1',
            'service',
            'https://www.finalurl.cl',
            '19000',
            '545666666',
            '5691111111',
            'namePat',
            'email@prueba.cl',
            'commerce.email@prueba.cl',
            'fakeAddress',
            'Santiago'
        );
        $this->assertInstanceOf(InscriptionStartResponse::class, $start);
    }

    /** @test */
    public function it_throws_inscription_start_exception()
    {
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_PRODUCTION);
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('fake exception'));
        $inscription = new Inscription($options, $requestServiceMock);
        $this->expectException(InscriptionStartException::class);
        $inscription->start(
            'https://www.url.cl',
            'Juanito',
            'Perez',
            'Perez',
            '11111111-1',
            'service',
            'https://www.finalurl.cl',
            '19000',
            '545666666',
            '5691111111',
            'namePat',
            'email@prueba.cl',
            'commerce.email@prueba.cl',
            'fakeAddress',
            'Santiago'
        );
    }

    /** @test */
    public function it_returns_inscription_status_response()
    {
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_PRODUCTION);
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock->method('request')
            ->willReturn(
                [
                    'authorized' => true,
                    'voucherUrl' => 'http://fakeurl.cl'
                ]
            );
        $inscription = new Inscription($options, $requestServiceMock);
        $start = $inscription->status('token');
        $this->assertInstanceOf(InscriptionStatusResponse::class, $start);
    }

    /** @test */
    public function it_throws_inscription_status_exception()
    {
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_PRODUCTION);
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('fake exception'));
        $inscription = new Inscription($options, $requestServiceMock);
        $this->expectException(InscriptionStatusException::class);
        $inscription->status('token');
    }

    /** @test */
    public function it_can_set_options()
    {
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_PRODUCTION);
        $inscription = new Inscription($options);
        $newOptions = new Options('apiKeyNew', 'commerceCodeNew', Options::ENVIRONMENT_INTEGRATION);
        $inscription->setOptions($newOptions);
        $this->assertEquals(Options::BASE_URL_INTEGRATION, $inscription->getOptions()->getApiBaseUrl());
    }

    /** @test */
    public function it_can_get_base_url()
    {
        $options = new Options('test-api-key', 'test-commerce-code', Options::ENVIRONMENT_INTEGRATION);
        $inscription = new Inscription($options);

        $reflection = new \ReflectionClass(Inscription::class);
        $method = $reflection->getMethod('getBaseUrl');
        $method->setAccessible(true);
        $baseUrl = $method->invoke($inscription);

        $this->assertEquals(Options::BASE_URL_INTEGRATION, $baseUrl);
    }
}
