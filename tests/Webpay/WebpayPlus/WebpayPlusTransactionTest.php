<?php

namespace Webpay\WebpayPlus;

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

class WebpayPlusTransactionTest extends TestCase
{
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
        $this->sessionId = 'some_session_id_'.uniqid();
        $this->buyOrder = '123999555';
        $this->returnUrl = 'https://comercio.cl/callbacks/transaccion_finalizada';
        $this->mockBaseUrl = 'http://mockurl.cl';
    }

    /** @test */
    public function it_uses_the_default_configuration_if_none_given()
    {
        WebpayPlus::reset();
        $transaction = (new Transaction());
        $this->assertEquals($transaction->getOptions(), $transaction->getDefaultOptions());
    }

    /** @test */
    public function it_can_set_a_specific_option()
    {
        $options = Options::forProduction('597012345678', 'fakeApiKey');

        $transaction = (new Transaction($options));
        $this->assertSame($transaction->getOptions(), $options);
    }

    /** @test */
    public function it_can_set_a_specific_option_globally()
    {
        WebpayPlus::configureForProduction('597012345678', 'fakeApiKey');
        $options = WebpayPlus::getOptions();

        $transaction = (new Transaction());
        $this->assertSame($transaction->getOptions(), $options);

        WebpayPlus::setOptions(null);
    }

    /** @test */
    public function it_can_change_the_request_service()
    {
        $requestServiceMock = $this->createMock(HttpClientRequestService::class);
        $requestServiceMock->expects($this->once())->method('request')->willReturn(
            [
                'token' => 'mock',
                'url'   => 'http://mock.cl/',
            ]
        );

        $transaction = (new Transaction(null, $requestServiceMock));
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
                    'url'   => 'http://mock.cl/',
                ]
            );

        $transaction = new Transaction($optionsMock, $requestServiceMock);
        $response = $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        $this->assertInstanceOf(TransactionCreateResponse::class, $response);
        $this->assertEquals($response->getToken(), $tokenMock);
        $this->assertEquals($response->getUrl(), 'http://mock.cl/');
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
            ->with('PUT', $expectedUrl, null)
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
        $this->assertSame($response->getResponseCode(), 0);
        $this->assertSame($response->getVci(), 'TSY');
        $this->assertSame($response->getSessionId(), 'session1234564');
        $this->assertSame($response->getStatus(), 'AUTHORIZED');
        $this->assertSame($response->getAmount(), 1000);
        $this->assertSame($response->getBuyOrder(), 'OrdenCompra36271');
        $this->assertSame($response->getCardNumber(), '6623');
        $this->assertSame($response->getCardDetail(), ['card_number' => '6623']);
        $this->assertSame($response->getAuthorizationCode(), '1213');
        $this->assertSame($response->getPaymentTypeCode(), 'VN');
        $this->assertSame($response->getInstallmentsNumber(), 0);
        $this->assertSame($response->getInstallmentsAmount(), null);
        $this->assertSame($response->getTransactionDate(), '2021-03-22T21:01:20.374Z');
    }

    /** @test */
    public function it_returns_the_default_options()
    {
        $options = Transaction::getDefaultOptions();
        $this->assertSame($options->getCommerceCode(), WebpayPlus::DEFAULT_COMMERCE_CODE);
        $this->assertSame($options->getApiKey(), WebpayPlus::DEFAULT_API_KEY);
        $this->assertSame($options->getIntegrationType(), Options::ENVIRONMENT_INTEGRATION);
    }

    /** @test */
    public function it_uses_the_given_options_and_not_global_ones()
    {
        WebpayPlus::configureForProduction('597012345678', 'fakeApiKey');
        $globalOptions = WebpayPlus::getOptions();

        $options = Options::forProduction('597087654321', 'fakeApiKey2');

        $transaction = (new Transaction($options));
        $this->assertSame($transaction->getOptions(), $options);
        $this->assertNotSame($transaction->getOptions(), $globalOptions);

        WebpayPlus::setOptions(null);
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_creations_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(TransactionCreateException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_commit_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(TransactionCommitException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->commit('fakeToken');
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_status_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(TransactionStatusException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->status('fakeToken');
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_refund_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(TransactionRefundException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->refund('fakeToken', 'buyOrder');
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_capture_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(TransactionCaptureException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->capture('fake', 'fake', 'fake', 1000);
    }
}
