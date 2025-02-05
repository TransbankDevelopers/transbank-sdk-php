<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCreateResponse;

class WebpayCreateResponseTest extends TestCase
{
    private TransactionCreateResponse $create;

    public function setUp(): void
    {
        $data = [
            'token' => 'fakeToken',
            'url' => 'https://www.urlpruebas.cl'
        ];
        $this->create = new TransactionCreateResponse($data);
    }

    #[Test]
    public function it_can_set_from_array()
    {
        $this->create->fromJSON([
            'token' => 'newFakeToken',
            'url' => 'https://newurl.cl'
        ]);
        $this->assertEquals('newFakeToken', $this->create->getToken());
        $this->assertEquals('https://newurl.cl', $this->create->getUrl());
    }
}
