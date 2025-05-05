<?php

namespace Test\Webpay\TransaccionCompleta;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
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

    #[Test]
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

    #[Test]
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

    #[Test]
    public function it_returns_installments_response()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    "installments_amount" => 3334,
                    "id_query_installments" => 11,
                    "deferred_periods" => [

                        [
                            "amount" => 1000,
                            "period" => 1
                        ]

                    ]
                ]
            );
        $installments = $this->mallTransaction->installments($tokenMock, [[
            'commerce_code'       => 'commerceCode',
            'buy_order'           => 'buyOrder',
            'installments_number' => 3
        ]]);

        $this->assertInstanceOf(MallTransactionInstallmentsResponse::class, $installments[0]);
    }

    #[Test]
    public function it_throws_installments_exception()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('Error on request', null, 404));
        $this->expectException(MallTransactionInstallmentsException::class);
        $this->mallTransaction->installments('token', [[
            'commerce_code'       => 'commerceCode',
            'buy_order'           => 'buyOrder',
            'installments_number' => 3
        ]]);
    }

    #[Test]
    public function it_returns_commit_response()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    "buy_order" => "415034240",
                    "card_detail" =>
                    ["card_number" => "6623"],
                    "accounting_date" => "0321",
                    "transaction_date" => "2019-03-21T15:43:48.523Z",
                    "details" => [

                        [
                            "amount" => 500,
                            "status" => "AUTHORIZED",
                            "authorization_code" => "1213",
                            "payment_type_code" => "VN",
                            "response_code" => 0,
                            "installments_number" => 0,
                            "commerce_code" => "597055555552",
                            "buy_order" => "505479072"
                        ]

                    ]
                ]
            );
        $commit = $this->mallTransaction->commit($tokenMock, [[
            'commerce_code'       => 'commerceCode',
            'buy_order'           => 'buyOrder',
            'id_query_installments' => 3,
            'deferred_period_index' => 3,
            'grace_period' => 3
        ]]);

        $this->assertInstanceOf(MallTransactionCommitResponse::class, $commit);
    }
    #[Test]
    public function it_throws_commit_exception()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('Error on request', null, 404));
        $this->expectException(MallTransactionCommitException::class);
        $this->mallTransaction->commit($tokenMock, [[
            'commerce_code'       => 'commerceCode',
            'buy_order'           => 'buyOrder',
            'id_query_installments' => 3,
            'deferred_period_index' => 3,
            'grace_period' => 3
        ]]);
    }

    #[Test]
    public function it_returns_refund_response()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    "type" => "NULLIFY",
                    "authorization_code" => "123456",
                    "authorization_date" => "2019-03-20T20:18:20Z",
                    "nullified_amount" => 1000,
                    "balance" => 0,
                    "response_code" => 0
                ]
            );
        $refund = $this->mallTransaction->refund($tokenMock, 'buyOrder', 'commerceChild', 1990);

        $this->assertInstanceOf(MallTransactionRefundResponse::class, $refund);
    }

    #[Test]
    public function it_throws_refund_exception()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('Error on request', null, 404));
        $this->expectException(MallTransactionRefundException::class);
        $this->mallTransaction->refund($tokenMock, 'buyOrder', 'commerceChild', 1990);
    }

    #[Test]
    public function it_returns_status_response()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    "buy_order" => "415034240",
                    "card_detail" =>
                    ["card_number" => "6623"],
                    "accounting_date" => "0321",
                    "transaction_date" => "2019-03-21T15:43:48.523Z",
                    "details" => [
                        [
                            "amount" => 500,
                            "status" => "AUTHORIZED",
                            "authorization_code" => "1213",
                            "payment_type_code" => "VN",
                            "response_code" => 0,
                            "installments_number" => 0,
                            "commerce_code" => "597055555552",
                            "buy_order" => "505479072"
                        ]
                    ]
                ]
            );
        $status = $this->mallTransaction->status($tokenMock);

        $this->assertInstanceOf(MallTransactionStatusResponse::class, $status);
    }

    #[Test]
    public function it_throws_status_exception()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('Error on request', null, 404));
        $this->expectException(MallTransactionStatusException::class);
        $this->mallTransaction->status($tokenMock);
    }

    #[Test]
    public function it_returns_capture_response()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    "token" => "e074d38c628122c63e5c0986368ece22974d6fee1440617d85873b7b4efa48a3",
                    "authorization_code" => "123456",
                    "authorization_date" => "2019-03-20T20:18:20Z",
                    "captured_amount" => 1000,
                    "response_code" => 0
                ]
            );
        $capture = $this->mallTransaction->capture($tokenMock, 'commerceCode', 'buyOrder', 'authCode', 9800);
        $this->assertInstanceOf(MallTransactionCaptureResponse::class, $capture);
    }

    #[Test]
    public function it_throws_capture_exception()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();
        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('Error on request', null, 404));
        $this->expectException(MallTransactionCaptureException::class);
        $this->mallTransaction->capture($tokenMock, 'commerceCode', 'buyOrder', 'authCode', 9800);
    }
}
