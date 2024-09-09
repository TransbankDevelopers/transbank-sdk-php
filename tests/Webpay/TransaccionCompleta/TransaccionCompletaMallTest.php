<?php

namespace Test\Webpay\TransaccionCompleta;

use PHPUnit\Framework\TestCase;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use Transbank\Webpay\TransaccionCompleta\MallTransaction;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionCreateResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionInstallmentsResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionCommitResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionRefundResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionStatusResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionCreateException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionInstallmentsException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionCommitException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionRefundException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\MallTransactionCaptureException;

class TransaccionCompletaMallTest extends TestCase
{

    protected $amount;
    protected $sessionId;
    protected $buyOrder;
    protected $cardNumber;
    protected $cvv;
    protected $mockBaseUrl;
    protected $requestServiceMock;
    protected $optionsMock;
    protected $headersMock;
    protected $cardExpiration;

    protected $mallTransaction;
    public function setBaseMocks()
    {
        $this->requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $this->optionsMock = $this->createMock(Options::class);
        $this->headersMock = ['header_1' => uniqid()];
        $this->optionsMock->method('getApiBaseUrl')->willReturn($this->mockBaseUrl);
        $this->optionsMock->method('getHeaders')->willReturn($this->headersMock);
        $this->mallTransaction = new MallTransaction(new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION), $this->requestServiceMock);
    }

    protected function setUp(): void
    {
        $this->amount = 1000;
        $this->sessionId = 'some_session_id_' . uniqid();
        $this->buyOrder = '123999555';
        $this->mockBaseUrl = 'https://mockurl.cl';
        $this->cvv = '123';
        $this->cardNumber = '4051885600446623';
        $this->cardExpiration = '12/24';
    }

    /** @test */
    public function it_creates_mall_transaction()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    'token' => $tokenMock,
                ]
            );
        $create = $this->mallTransaction->create(
            $this->buyOrder,
            $this->sessionId,
            $this->cardNumber,
            $this->cardExpiration,
            [[
                "amount" => 10000,
                "commerce_code" => "597055555552",
                "buy_order" => "123456789"
            ]],
            $this->cvv
        );

        $this->assertEquals($tokenMock, $create->getToken());
        $this->assertInstanceOf(MallTransactionCreateResponse::class, $create);
    }

    /** @test */
    public function it_throws_create_exception()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('Error on request', null, 404));
        $this->expectException(MallTransactionCreateException::class);
        $this->mallTransaction->create(
            $this->buyOrder,
            $this->sessionId,
            $this->cardNumber,
            $this->cardExpiration,
            [[
                "amount" => 10000,
                "commerce_code" => "597055555552",
                "buy_order" => "123456789"
            ]],
            $this->cvv
        );
    }

