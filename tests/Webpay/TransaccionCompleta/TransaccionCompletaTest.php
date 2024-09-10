<?php

namespace Test\Webpay\TransaccionCompleta;

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCommitException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCreateException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionInstallmentsException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionRefundException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionStatusException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCaptureException;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCommitResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCreateResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionInstallmentsResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionStatusResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCaptureResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionRefundResponse;
use Transbank\Webpay\TransaccionCompleta\Transaction;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

class TransaccionCompletaTest extends TestCase
{
    const MOCK_SEARCH_STRING = '{token}';
    const MOCK_TRANSACTION_DATE = '2021-03-29T06:33:32.954Z';
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
    protected $cardNumber;

    protected $cvv;
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
    /**
     * @var string
     */
    protected $cardExpiration;

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
        $this->mockBaseUrl = 'https://mockurl.cl';
        $this->cvv = '123';
        $this->cardNumber = '4051885600446623';
        $this->cardExpiration = '12/24';
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
    public function it_creates_a_transaction()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $this->requestServiceMock->method('request')
            ->with('POST', Transaction::ENDPOINT_CREATE, [
                'buy_order'            => $this->buyOrder,
                'session_id'           => $this->sessionId,
                'amount'               => $this->amount,
                'card_number'          => $this->cardNumber,
                'card_expiration_date' => $this->cardExpiration,
                'cvv'                  => $this->cvv,
            ])
            ->willReturn(
                [
                    'token' => $tokenMock,
                ]
            );

        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->cardNumber,
            $this->cardExpiration,
            $this->cvv
        );
        $this->assertInstanceOf(TransactionCreateResponse::class, $response);
        $this->assertEquals($response->getToken(), $tokenMock);
    }

    /** @test */
    public function it_gets_installments()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $this->requestServiceMock->method('request')
            ->with('POST', str_replace(self::MOCK_SEARCH_STRING, $tokenMock, Transaction::ENDPOINT_INSTALLMENTS), [
                'installments_number' => 2,
            ])
            ->willReturn([
                'installments_amount'   => 1000,
                'id_query_installments' => 33189687,
                'deferred_periods'      => [],
            ]);

        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->installments($tokenMock, 2);
        $this->assertInstanceOf(TransactionInstallmentsResponse::class, $response);
        $this->assertEquals(1000, $response->getInstallmentsAmount());
        $this->assertEquals(33189687, $response->getIdQueryInstallments());
        $this->assertEquals([], $response->getDeferredPeriods());
    }

    /** @test */
    public function it_commits_a_transaction()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $expectedUrl = str_replace(
            self::MOCK_SEARCH_STRING,
            $tokenMock,
            Transaction::ENDPOINT_COMMIT
        );

        $this->requestServiceMock->method('request')
            ->with('PUT', $expectedUrl, $this->anything())
            ->willReturn([
                'amount'      => 10000,
                'status'      => 'AUTHORIZED',
                'buy_order'   => 'OrdenCompra55886',
                'session_id'  => 'sesion1234564',
                'card_detail' => [
                    'card_number' => '6623',
                ],
                'accounting_date'     => '0329',
                'transaction_date'    => self::MOCK_TRANSACTION_DATE,
                'authorization_code'  => '1213',
                'payment_type_code'   => 'NC',
                'response_code'       => 0,
                'installments_amount' => 1000,
                'installments_number' => 10,
            ]);

        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->commit($tokenMock);
        $this->assertInstanceOf(TransactionCommitResponse::class, $response);
        $this->assertSame(null, $response->getVci());
        $this->assertSame('sesion1234564', $response->getSessionId());
        $this->assertSame('AUTHORIZED', $response->getStatus());
        $this->assertSame(10000, $response->getAmount());
        $this->assertSame('OrdenCompra55886', $response->getBuyOrder());
        $this->assertSame('6623', $response->getCardNumber());
        $this->assertSame(['card_number' => '6623'], $response->getCardDetail());
        $this->assertSame('1213', $response->getAuthorizationCode());
        $this->assertSame('NC', $response->getPaymentTypeCode());
        $this->assertSame(10, $response->getInstallmentsNumber());
        $this->assertSame(1000, $response->getInstallmentsAmount());
        $this->assertSame(self::MOCK_TRANSACTION_DATE, $response->getTransactionDate());
        $this->assertSame('0329', $response->getAccountingDate());
    }

    /** @test */
    public function it_gets_a_transaction_status()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $expectedUrl = str_replace(
            self::MOCK_SEARCH_STRING,
            $tokenMock,
            Transaction::ENDPOINT_STATUS
        );

        $this->requestServiceMock->method('request')
            ->with('GET', $expectedUrl, $this->anything())
            ->willReturn([
                'amount'      => 10000,
                'status'      => 'AUTHORIZED',
                'buy_order'   => 'OrdenCompra55886',
                'session_id'  => 'sesion1234564',
                'card_detail' => [
                    'card_number' => '6623',
                ],
                'accounting_date'     => '0329',
                'transaction_date'    => self::MOCK_TRANSACTION_DATE,
                'authorization_code'  => '1213',
                'payment_type_code'   => 'NC',
                'response_code'       => 0,
                'installments_amount' => 1000,
                'installments_number' => 10,
            ]);

        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->status($tokenMock);
        $this->assertInstanceOf(TransactionStatusResponse::class, $response);
        $this->assertSame(null, $response->getVci());
        $this->assertSame('sesion1234564', $response->getSessionId());
        $this->assertSame('AUTHORIZED', $response->getStatus());
        $this->assertSame(10000, $response->getAmount());
        $this->assertSame('OrdenCompra55886', $response->getBuyOrder());
        $this->assertSame('6623', $response->getCardNumber());
        $this->assertSame(['card_number' => '6623'], $response->getCardDetail());
        $this->assertSame('1213', $response->getAuthorizationCode());
        $this->assertSame('NC', $response->getPaymentTypeCode());
        $this->assertSame(10, $response->getInstallmentsNumber());
        $this->assertSame(1000, $response->getInstallmentsAmount());
        $this->assertSame(self::MOCK_TRANSACTION_DATE, $response->getTransactionDate());
        $this->assertSame('0329', $response->getAccountingDate());
    }

    /** @test */
    public function it_returns_capture_response()
    {
        $this->setBaseMocks();
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
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $capture = $transaction->capture('token', 'buyOrder', 'authCode', 2000);
        $this->assertInstanceOf(TransactionCaptureResponse::class, $capture);
    }

    /** @test */
    public function it_returns_refund_response_for_reverse()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    "type" => "REVERSE",
                ]
            );
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $refund = $transaction->refund('token', 2000);

        $this->assertInstanceOf(TransactionRefundResponse::class, $refund);
        $this->assertSame('REVERSE', $refund->getType());
    }

    /** @test */
    public function it_returns_refund_response_for_nullify()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willReturn(
                [
                    "type" => "NULLIFY",
                    "authorization_code" => "123456",
                    "authorization_date" => "2024-09-10T12:56:20Z",
                    "nullified_amount" => "1000.00",
                    "balance" => "1000.00",
                    "response_code" => 0,
                ]
            );
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $refund = $transaction->refund('token', 2000);

        $this->assertInstanceOf(TransactionRefundResponse::class, $refund);
        $this->assertSame('NULLIFY', $refund->getType());
        $this->assertSame('123456', $refund->getAuthorizationCode());
        $this->assertSame('2024-09-10T12:56:20Z', $refund->getAuthorizationDate());
        $this->assertSame(1000.0, $refund->getNullifiedAmount());
        $this->assertSame(1000.0, $refund->getBalance());
        $this->assertSame(0, $refund->getResponseCode());
    }

    /*
    |--------------------------------------------------------------------------
    | Fails
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function it_throws_and_exception_if_transaction_creations_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(TransactionCreateException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->cvv, $this->cardNumber, $this->cardExpiration);
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
    public function it_throws_and_exception_if_transaction_installments_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(TransactionInstallmentsException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->installments('fakeToken', 2);
    }

    /** @test */
    public function it_throws_capture_exception()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('fake request exception'));
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $this->expectException(TransactionCaptureException::class);
        $transaction->capture('token', 'buyOrder', 'authCode', 2000);
    }
}
