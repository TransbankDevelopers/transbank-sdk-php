<?php

namespace Webpay\WebpayPlus;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionStatusException;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\WebpayPlus\Responses\TransactionRefundResponse;

class WebpayPlusTransactionTest extends TestCase
{

    const MOCK_URL = 'https://mockurl.cl';
    const MOCK_ERROR_MESSAGE = 'error message';
    /**
     * @var int
     */
    protected $amount;
    /**
     * @var string
     */
    protected $sessionId;
    /**
     * @var string
     */
    protected $buyOrder;
    /**
     * @var string
     */
    protected $returnUrl;
    /**
     * @var string
     */
    protected $mockBaseUrl;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|HttpClientRequestService
     */
    protected $requestServiceMock;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Options
     */
    protected $optionsMock;
    /**
     * @var array
     */
    protected $headersMock;

    public function setBaseMocks()
    {
        $this->requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $this->optionsMock = $this->createMock(Options::class);

        $this->headersMock = ['header_1' => uniqid()];
        $this->optionsMock->method('getApiBaseUrl')->willReturn($this->mockBaseUrl);
        $this->optionsMock->method('getHeaders')->willReturn($this->headersMock);
    }

    protected function setUp(): void
    {
        $this->amount = 1000;
        $this->sessionId = 'some_session_id_' . uniqid();
        $this->buyOrder = '123999555';
        $this->returnUrl = 'https://comercio.cl/callbacks/transaccion_finalizada';
        $this->mockBaseUrl = self::MOCK_URL;
    }

    /** @test */
    public function it_configures_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = Transaction::buildForIntegration($commerceCode, $apiKey);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_INTEGRATION, $transactionOptions->getApiBaseUrl());
    }

    /** @test */
    public function it_configures_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = Transaction::buildForProduction($commerceCode, $apiKey);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $transactionOptions->getApiBaseUrl());
    }

    /** @test */
    public function it_configures_with_options()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $options = new Options($apiKey, $commerceCode, Options::ENVIRONMENT_PRODUCTION);
        $transaction = new Transaction($options);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $transactionOptions->getApiBaseUrl());
    }

    /** @test */
    public function it_can_change_the_request_service()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock->expects($this->once())->method('request')->willReturn(
            [
                'token' => 'mock',
                'url'   => self::MOCK_URL,
            ]
        );
        $options = new Options(WebpayPlus::INTEGRATION_API_KEY, WebpayPlus::INTEGRATION_COMMERCE_CODE, Options::ENVIRONMENT_INTEGRATION);
        $transaction = (new Transaction($options, $requestServiceMock));
        $this->assertSame($transaction->getRequestService(), $requestServiceMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }

    /** @test */
    public function it_creates_a_transaction()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $optionsMock = $this->createMock(Options::class);

        $tokenMock = uniqid();

        $optionsMock->method('getApiBaseUrl')->willReturn($this->mockBaseUrl);

        $requestServiceMock->method('request')
            ->with('POST', Transaction::ENDPOINT_CREATE, [
                'buy_order'  => $this->buyOrder,
                'session_id' => $this->sessionId,
                'amount'     => $this->amount,
                'return_url' => $this->returnUrl,
            ])
            ->willReturn(
                [
                    'token' => $tokenMock,
                    'url'   => self::MOCK_URL,
                ]
            );

        $transaction = new Transaction($optionsMock, $requestServiceMock);
        $response = $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        $this->assertInstanceOf(TransactionCreateResponse::class, $response);
        $this->assertEquals($response->getToken(), $tokenMock);
        $this->assertEquals($response->getUrl(), self::MOCK_URL);
    }

    /** @test */
    public function it_commits_a_transaction()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $expectedUrl = str_replace(
            '{token}',
            $tokenMock,
            Transaction::ENDPOINT_COMMIT
        );

        $this->requestServiceMock->method('request')
            ->with('PUT', $expectedUrl, [])
            ->willReturn([
                'vci'         => 'TSY',
                'amount'      => 1000,
                'status'      => 'AUTHORIZED',
                'buy_order'   => 'OrdenCompra36271',
                'session_id'  => 'session1234564',
                'card_detail' => [
                    'card_number' => '6623',
                ],
                'accounting_date'     => '0322',
                'transaction_date'    => '2021-03-22T21:01:20.374Z',
                'authorization_code'  => '1213',
                'payment_type_code'   => 'VN',
                'response_code'       => 0,
                'installments_number' => 0,
            ]);

        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->commit($tokenMock);
        $this->assertInstanceOf(TransactionCommitResponse::class, $response);
        $this->assertSame(0, $response->getResponseCode());
        $this->assertSame('TSY', $response->getVci());
        $this->assertSame('session1234564', $response->getSessionId());
        $this->assertSame('AUTHORIZED', $response->getStatus());
        $this->assertSame(1000, $response->getAmount());
        $this->assertSame('OrdenCompra36271', $response->getBuyOrder());
        $this->assertSame('6623', $response->getCardNumber());
        $this->assertSame(['card_number' => '6623'], $response->getCardDetail());
        $this->assertSame('1213', $response->getAuthorizationCode());
        $this->assertSame('VN', $response->getPaymentTypeCode());
        $this->assertSame(0, $response->getInstallmentsNumber());
        $this->assertSame(null, $response->getInstallmentsAmount());
        $this->assertSame('2021-03-22T21:01:20.374Z', $response->getTransactionDate());
        $this->assertSame(true, $response->isApproved());
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_creations_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(TransactionCreateException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_commit_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(TransactionCommitException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->commit('fakeToken');
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_status_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(TransactionStatusException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->status('fakeToken');
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_refund_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(TransactionRefundException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->refund('fakeToken', 123);
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_capture_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(TransactionCaptureException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->capture('fake', 'fake', 'fake', 1000);
    }

    /** @test */
    public function it_can_get_data_from_capture()
    {
        $this->setBaseMocks();
        $this->requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                'authorization_code' => 'abc123',
                'authorization_date' => '2015-02-16',
                'captured_amount' => 1200,
                'response_code' => 0
            ]);
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION);
        $transaction = new Transaction($options, $this->requestServiceMock);
        $capture = $transaction->capture('fakeToken', 'fakeBuyOrder', 'abc123', '1200');

        $this->assertTrue($capture->isApproved());
        $this->assertEquals('abc123', $capture->getAuthorizationCode());
        $this->assertEquals('2015-02-16', $capture->getAuthorizationDate());
        $this->assertEquals(1200, $capture->getCapturedAmount());
        $this->assertEquals(0, $capture->getResponseCode());
    }

}
