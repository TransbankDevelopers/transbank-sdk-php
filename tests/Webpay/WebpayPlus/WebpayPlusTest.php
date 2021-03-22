<?php

namespace Webpay\WebpayPlus;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Transbank\Utils\HttpClient;
use Transbank\Utils\InteractsWithWebpayApi;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\TransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Transaction;

class WebpayPlusTest extends TestCase
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
     * @var \PHPUnit\Framework\MockObject\MockObject|HttpClient
     */
    protected $httpClientMock;
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
        $this->httpClientMock = $this->createMock(HttpClient::class);
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
        $this->returnUrl = 'https://comercio.cl/callbacks/transaccion_creada_exitosamente';
        $this->mockBaseUrl = 'http://mockurl.cl';
    }
    
    
    /** @test */
    public function it_can_set_a_specific_option()
    {
        WebpayPlus::transaction()->create();
        $options = Options::forIntegration('597012345678', 'fakeApiKey');
        $transaction = (new Transaction($options));
        $this->assertSame($transaction->getOptions(), $options);
    }
    
    /** @test */
    public function it_can_change_the_http_request_client()
    {
        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock->expects($this->once())->method('perform')->willReturn(
            new Response(200, [], json_encode([
                'token' => 'mock',
                'url' => 'http://mock.cl/'
            ]))
        );
        
        $transaction = (new Transaction(null, $httpClientMock));
        $this->assertSame($transaction->getHttpClient(), $httpClientMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }
    
    /** @test */
    public function it_send_the_headers_provided_by_the_given_options()
    {
        $expectedHeaders = ['api_key' => 'commerce_code', 'api_secret' => 'fakeApiKey'];
        
        $optionsMock = $this->createMock(Options::class);
        $optionsMock
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn($expectedHeaders);
        
        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock
            ->expects($this->once())
            ->method('perform')
            ->with($this->anything(), $this->anything(), $this->anything(), $this->equalTo([
                'headers' => $expectedHeaders
            ]))
            ->willReturn(
                new Response(200, [], json_encode([
                    'token' => 'mock',
                    'url' => 'http://mock.cl/'
                ]))
            );
    
        $transaction = (new Transaction($optionsMock, $httpClientMock));
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }
    
    /** @test */
    public function it_uses_the_base_url_provided_by_the_given_options()
    {
        $expectedBaseUrl = 'http://mock.mock/';
        
        $optionsMock = $this->createMock(Options::class);
        $optionsMock
            ->expects($this->once())
            ->method('getApiBaseUrl')
            ->willReturn($expectedBaseUrl);
        
        $httpClientMock = $this->createMock(HttpClient::class);
        $httpClientMock
            ->expects($this->once())
            ->method('perform')
            ->with($this->anything(), $this->stringContains($expectedBaseUrl), $this->anything())
            ->willReturn(
                new Response(200, [], json_encode([
                    'token' => 'mock',
                    'url' => 'http://mock.cl/'
                ]))
            );
        
        $transaction = (new Transaction($optionsMock, $httpClientMock));
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }
    
    /** @test */
    public function it_creates_a_transaction()
    {
        $httpClientMock = $this->createMock(HttpClient::class);
        $optionsMock = $this->createMock(Options::class);
        
        $tokenMock = uniqid();
        
        $optionsMock->method('getApiBaseUrl')->willReturn($this->mockBaseUrl);
        
        $httpClientMock->method('perform')
            ->with('POST', $this->mockBaseUrl . Transaction::ENDPOINT_CREATE_TRANSACTION, [
                'buy_order' => $this->buyOrder,
                'session_id' => $this->sessionId,
                'amount' => $this->amount,
                'return_url' => $this->returnUrl,
            ])
            ->willReturn(
                new Response(200, [], json_encode([
                    'token' => $tokenMock,
                    'url' => 'http://mock.cl/'
                ]))
            );
        
        $transaction = new Transaction($optionsMock, $httpClientMock);
        $response = $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        $this->assertInstanceOf(TransactionCreateResponse::class, $response);
        $this->assertEquals($response->getToken(), $tokenMock);
        $this->assertEquals($response->getUrl(), 'http://mock.cl/');
    }
    
    /** @test */
    public function it_throws_and_exception_if_transaction_creations_fails()
    {
        $this->setBaseMocks();
    
        $this->httpClientMock->method('perform')
            ->with(
                'POST',
                $this->mockBaseUrl . Transaction::ENDPOINT_CREATE_TRANSACTION,
                [
                    'buy_order' => $this->buyOrder,
                    'session_id' => $this->sessionId,
                    'amount' => $this->amount,
                    'return_url' => null,
                ],
                ['headers' => $this->headersMock]
            )
            ->willReturn(
                new Response(422, [], json_encode([
                    'error_message' => 'return_url is required!'
                ]))
            );
        
        $this->expectException(TransactionCreateException::class);
        $this->expectExceptionMessage('return_url is required!');
        $transaction = new Transaction($this->optionsMock, $this->httpClientMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, null);
    }
    
    /** @test */
    public function it_commits_a_transaction()
    {
        $this->setBaseMocks();
        
        $tokenMock = uniqid();
    
        $expectedUrl = str_replace(
            '{token}',
            $tokenMock,
            $this->mockBaseUrl . Transaction::ENDPOINT_COMMIT_TRANSACTION
        );
        
        $this->httpClientMock->method('perform')
            ->with('PUT', $expectedUrl, null, ['headers' => $this->headersMock])
            ->willReturn(
                new Response(200, [], '
{
    "vci": "TSY",
    "amount": 1000,
    "status": "AUTHORIZED",
    "buy_order": "OrdenCompra36271",
    "session_id": "session1234564",
    "card_detail": {
        "card_number": "6623"
    },
    "accounting_date": "0322",
    "transaction_date": "2021-03-22T21:01:20.374Z",
    "authorization_code": "1213",
    "payment_type_code": "VN",
    "response_code": 0,
    "installments_number": 0
}')
            );
    
        $transaction = new Transaction($this->optionsMock, $this->httpClientMock);
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
    
    public function testCreateATransactionWithoutOptions()
    {
        $transactionResult = Transaction::create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        
        $this->assertNotNull($transactionResult->getToken());
        $this->assertNotNull($transactionResult->getUrl());
    }
    
    public function testCreateATransactionWithOptions()
    {
        $options = new Options('579B532A7440BB0C9079DED94D31EA1615BACEB56610332264630D42D0A36B1C', '597055555532');
        $transactionResult = Transaction::create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl,
            $options
        );
        
        $this->assertNotNull($transactionResult->getToken());
        $this->assertNotNull($transactionResult->getUrl());
    }
    
    public function testCreateTransactionWithIncorrectCredentialsShouldFail()
    {
        $options = new Options('fakeApiKey', 'fakeCommerceCode');
        
        $this->setExpectedException(\Exception::class, 'Not Authorized');
        Transaction::create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl, $options);
    }
    
    /** @test */
    public function it_can_get_the_status_of_a_transaction()
    {
        $response = Transaction::create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        
        $response = Transaction::status($response->getToken());
        $this->assertEquals('INITIALIZED', $response->getStatus());
        $this->assertEquals($this->amount, $response->getAmount());
        $this->assertEquals($this->buyOrder, $response->getBuyOrder());
        $this->assertEquals($this->sessionId, $response->getSessionId());
    }
    
    /** @test */
    public function it_can_not_commit_a_just_created_transaction()
    {
        $response = $this->createTransaction();
        
        $this->setExpectedException(
            TransactionCommitException::class,
            "Invalid status '0' for transaction while authorizing"
        );
        Transaction::commit($response->getToken());
    }
    
    /** @test */
    public function it_can_not_capture_a_transaction_recently_created()
    {
        WebpayPlus::configureDeferredForTesting();
        $response = $this->createTransaction();
        $this->setExpectedException(TransactionCaptureException::class, 'Transaction not found');
        Transaction::capture($response->getToken(), $this->buyOrder, 'authCode', $this->amount);
        
        $this->assertTrue(true);
    }
    
    /** @test */
    public function it_can_not_capture_a_transaction_with_simultaneous_capture_commerce_code()
    {
        WebpayPlus::configureForTesting();
        $response = $this->createTransaction();
        $this->setExpectedException(TransactionCaptureException::class, 'Operation not allowed');
        Transaction::capture($response->getToken(), $this->buyOrder, 'authCode', $this->amount);
        
        $this->assertTrue(true);
    }
    
    /**
     * @return \Transbank\Webpay\WebpayPlus\Responses\TransactionCreateResponse
     * @throws WebpayPlus\Exceptions\TransactionCreateException
     *
     */
    public function createTransaction()
    {
        $response = Transaction::create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        
        return $response;
    }
}
