<?php

namespace Tests\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Mockery;
use Mockery\MockInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Transbank\Sdk\Credentials\Container;
use Transbank\Sdk\Http\Connector;
use Transbank\Sdk\Transbank;

trait TestsServices
{
    protected Transbank $transbank;
    protected HandlerStack $handlerStack;
    protected LoggerInterface|MockInterface $logger;
    protected EventDispatcherInterface|MockInterface $dispatcher;
    /** @var array<array<\GuzzleHttp\Psr7\ServerRequest>> */
    protected array $requests;

    protected function setUp(): void
    {
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->dispatcher = Mockery::mock(EventDispatcherInterface::class);

        $this->requests = [];
        $this->handlerStack = HandlerStack::create();
        $this->handlerStack->push(Middleware::history($this->requests));

        $connector = new Connector(
            new Client(['handler' => $this->handlerStack]), $factory = new Psr17Factory(), $factory
        );

        $this->transbank = new Transbank(new Container(), $this->logger, $this->dispatcher, $connector);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
