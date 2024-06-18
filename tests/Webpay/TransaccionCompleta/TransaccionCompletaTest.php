<?php

namespace Test\Webpay\TransaccionCompleta;

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCommitException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionCreateException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionInstallmentsException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionRefundException;
use Transbank\Webpay\TransaccionCompleta\Exceptions\TransactionStatusException;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCommitResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionCreateResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionInstallmentsResponse;
use Transbank\Webpay\TransaccionCompleta\Responses\TransactionStatusResponse;
use Transbank\Webpay\TransaccionCompleta\TransaccionCompleta;
use Transbank\Webpay\TransaccionCompleta\Transaction;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;

class TransaccionCompletaTest extends TestCase
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
                'cvv'                  => $this->cvv,
                'card_number'          => $this->cardNumber,
                'card_expiration_date' => $this->cardExpiration,
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
            $this->cvv,
            $this->cardNumber,
            $this->cardExpiration
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
            ->with('POST', str_replace('{token}', $tokenMock, Transaction::ENDPOINT_INSTALLMENTS), [
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
            '{token}',
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
                'transaction_date'    => '2021-03-29T06:33:32.954Z',
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
        $this->assertSame('2021-03-29T06:33:32.954Z', $response->getTransactionDate());
        $this->assertSame('0329', $response->getAccountingDate());
    }

    /** @test */
    public function it_gets_a_transaction_status()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $expectedUrl = str_replace(
            '{token}',
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
                'transaction_date'    => '2021-03-29T06:33:32.954Z',
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
        $this->assertSame('2021-03-29T06:33:32.954Z', $response->getTransactionDate());
        $this->assertSame('0329', $response->getAccountingDate());
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
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(TransactionCreateException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->cvv, $this->cardNumber, $this->cardExpiration);
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
        $transaction->refund('fakeToken', 'buyOrder', 'comemrceCode', 1400);
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_installments_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(TransactionInstallmentsException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new Transaction($this->optionsMock, $this->requestServiceMock);
        $transaction->installments('fakeToken', 2);
    }
}
