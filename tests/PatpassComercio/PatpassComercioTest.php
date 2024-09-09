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

        $inscription = Inscription::buildForIntegration($commerceCode, $apiKey);
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

        $inscription = Inscription::buildForProduction($commerceCode, $apiKey);
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
}
