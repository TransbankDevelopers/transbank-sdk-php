<?php

namespace Webpay\WebpayPlus;

use PHPUnit\Framework\TestCase;
use Transbank\Utils\HttpClientRequestService;
use Transbank\Webpay\Exceptions\WebpayRequestException;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionCreateException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionRefundException;
use Transbank\Webpay\WebpayPlus\Exceptions\MallTransactionStatusException;
use Transbank\Webpay\WebpayPlus\MallTransaction;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCommitResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCreateResponse;
use Transbank\Webpay\WebpayPlus\Transaction;

class WebpayMallTransactionTest extends TestCase
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
        $this->sessionId = 'some_session_id_' . uniqid();
        $this->buyOrder = '123999555';
        $this->returnUrl = 'https://comercio.cl/callbacks/transaccion_finalizada';
        $this->mockBaseUrl = 'https://mockurl.cl';
    }

    /** @test */
    public function it_configures_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = MallTransaction::buildForIntegration($commerceCode, $apiKey);
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

        $transaction = MallTransaction::buildForProduction($commerceCode, $apiKey);
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
        $transaction = new MallTransaction($options);
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

        $details = [
            'amount'        => $this->amount,
            'commerce_code' => WebpayPlus::INTEGRATION_MALL_CHILD_COMMERCE_CODE_1,
            'buy_order'     => 'BuyOrderChild',
        ];

        $this->requestServiceMock->method('request')
            ->with('POST', MallTransaction::ENDPOINT_CREATE, [
                'buy_order'  => $this->buyOrder,
                'session_id' => $this->sessionId,
                'details'    => $details,
                'return_url' => $this->returnUrl,
            ])
            ->willReturn(
                [
                    'token' => $tokenMock,
                    'url'   => 'https://mock.cl/',
                ]
            );

        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->create($this->buyOrder, $this->sessionId, $this->returnUrl, $details);
        $this->assertInstanceOf(MallTransactionCreateResponse::class, $response);
        $this->assertEquals($response->getToken(), $tokenMock);
        $this->assertEquals('https://mock.cl/', $response->getUrl());
    }

    /** @test */
    public function it_commits_a_transaction()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $expectedUrl = str_replace(
            '{token}',
            $tokenMock,
            MallTransaction::ENDPOINT_COMMIT
        );

        $this->requestServiceMock->method('request')
            ->with('PUT', $expectedUrl, null)
            ->willReturn([
                'vci'     => 'TSY',
                'details' => [
                    0 => [
                        'amount'              => 1000,
                        'status'              => 'AUTHORIZED',
                        'authorization_code'  => '1213',
                        'payment_type_code'   => 'VN',
                        'response_code'       => 0,
                        'installments_number' => 0,
                        'commerce_code'       => '597055555536',
                        'buy_order'           => 'OrdenCompraChild_66986_1',
                    ],
                    1 => [
                        'amount'              => 2000,
                        'status'              => 'AUTHORIZED',
                        'authorization_code'  => '1213',
                        'payment_type_code'   => 'VN',
                        'response_code'       => 0,
                        'installments_number' => 0,
                        'commerce_code'       => '597055555537',
                        'buy_order'           => 'OrdenCompraChild_66986_2',
                    ],
                ],
                'buy_order'   => 'OrdenCompra36271',
                'session_id'  => 'session1234564',
                'card_detail' => [
                    'card_number' => '6623',
                ],
                'accounting_date'  => '0329',
                'transaction_date' => '2021-03-29T04:47:19.885Z',
            ]);

        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->commit($tokenMock);

        $this->assertInstanceOf(MallTransactionCommitResponse::class, $response);
        $firstDetail = $response->getDetails()[0];
        $secondDetail = $response->getDetails()[1];
        $this->assertNotNull($firstDetail);
        $this->assertNotNull($secondDetail);
        $this->assertSame('TSY', $response->getVci());
        $this->assertSame('session1234564', $response->getSessionId());
        $this->assertSame('OrdenCompra36271', $response->getBuyOrder());
        $this->assertSame('6623', $response->getCardNumber());
        $this->assertSame(['card_number' => '6623'], $response->getCardDetail());
        $this->assertSame('0329', $response->getAccountingDate());
        $this->assertSame('2021-03-29T04:47:19.885Z', $response->getTransactionDate());
        $this->assertSame(0, $firstDetail->getResponseCode());
        $this->assertSame('AUTHORIZED', $firstDetail->getStatus());
        $this->assertSame(1000, $firstDetail->getAmount());
        $this->assertSame('1213', $firstDetail->getAuthorizationCode());
        $this->assertSame('VN', $firstDetail->getPaymentTypeCode());
        $this->assertSame(0, $firstDetail->getInstallmentsNumber());
        $this->assertSame(null, $firstDetail->getInstallmentsAmount());
        $this->assertSame('597055555536', $firstDetail->getCommerceCode());
        $this->assertSame('OrdenCompraChild_66986_1', $firstDetail->getBuyOrder());
        $this->assertSame(0, $secondDetail->getResponseCode());
        $this->assertSame('AUTHORIZED', $secondDetail->getStatus());
        $this->assertSame(2000, $secondDetail->getAmount());
        $this->assertSame('1213', $secondDetail->getAuthorizationCode());
        $this->assertSame('VN', $secondDetail->getPaymentTypeCode());
        $this->assertSame(0, $secondDetail->getInstallmentsNumber());
        $this->assertSame(null, $secondDetail->getInstallmentsAmount());
        $this->assertSame('597055555537', $secondDetail->getCommerceCode());
        $this->assertSame('OrdenCompraChild_66986_2', $secondDetail->getBuyOrder());
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

        $this->expectException(MallTransactionCreateException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, null);
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_commit_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(MallTransactionCommitException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->commit('fakeToken');
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_status_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(MallTransactionStatusException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->status('fakeToken');
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_refund_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(MallTransactionRefundException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->refund('fakeToken', 'buyOrder', 'comemrceCode', 1400);
    }

    /** @test */
    public function it_throws_and_exception_if_transaction_capture_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException('error message'));

        $this->expectException(MallTransactionCaptureException::class);
        $this->expectExceptionMessage('error message');
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->capture('fake', 'fake', 'fake', '1203', 1000);
    }
}
