<?php

namespace Webpay\WebpayPlus;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
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
use InvalidArgumentException;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionCaptureResponse;
use Transbank\Webpay\WebpayPlus\Responses\MallTransactionRefundResponse;

class WebpayMallTransactionTest extends TestCase
{
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
        $this->mockBaseUrl = 'https://mockurl.cl';
    }

    #[Test]
    public function it_configures_for_integration()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = MallTransaction::buildForIntegration($apiKey, $commerceCode);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_INTEGRATION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_INTEGRATION, $transactionOptions->getApiBaseUrl());
    }

    #[Test]
    public function it_configures_for_production()
    {
        $commerceCode = 'testCommerceCode';
        $apiKey = 'testApiKey';

        $transaction = MallTransaction::buildForProduction($apiKey, $commerceCode);
        $transactionOptions = $transaction->getOptions();

        $this->assertSame($commerceCode, $transactionOptions->getCommerceCode());
        $this->assertSame($apiKey, $transactionOptions->getApiKey());
        $this->assertSame(Options::ENVIRONMENT_PRODUCTION, $transactionOptions->getIntegrationType());
        $this->assertSame(Options::BASE_URL_PRODUCTION, $transactionOptions->getApiBaseUrl());
    }

    #[Test]
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

    #[Test]
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

    #[Test]
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
            ->with('PUT', $expectedUrl, [])
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

    #[Test]
    public function it_throws_and_exception_if_transaction_creations_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(MallTransactionCreateException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, []);
    }

    #[Test]
    public function it_throws_and_exception_if_transaction_commit_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(MallTransactionCommitException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->commit('fakeToken');
    }

    #[Test]
    public function it_throws_and_exception_if_transaction_status_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(MallTransactionStatusException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->status('fakeToken');
    }

    #[Test]
    public function it_throws_and_exception_if_transaction_refund_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(MallTransactionRefundException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->refund('fakeToken', 'buyOrder', 'comemrceCode', 1400);
    }

    #[Test]
    public function it_throws_and_exception_if_transaction_capture_fails()
    {
        $this->setBaseMocks();

        $this->requestServiceMock->method('request')
            ->willThrowException(new WebpayRequestException(self::MOCK_ERROR_MESSAGE));

        $this->expectException(MallTransactionCaptureException::class);
        $this->expectExceptionMessage(self::MOCK_ERROR_MESSAGE);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $transaction->capture('fake', 'fake', 'fake', '1203', 1000);
    }

    #[Test]
    public function it_can_get_expiration_date_from_status()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willReturn([
                'expiration_date' => '2021-02-16',
                'details' => [['response_code' => -1, 'status' => 'FAILED']]
            ]);

        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $status = $transaction->status('fakeToken');
        $this->assertEquals('2021-02-16', $status->getExpirationDate());
    }

    #[Test]
    public function it_can_check_is_approved()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willReturn([
                'expiration_date' => '2021-02-18',
                'details' => [['response_code' => 0, 'status' => 'AUTHORIZED']]
            ]);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $status = $transaction->status('fakeToken');
        $this->assertTrue($status->isApproved());
    }

    #[Test]
    public function it_can_check_is_rejected_when_details_not_exists()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willReturn([
                'expiration_date' => '2021-02-18',
                'details' => []
            ]);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $status = $transaction->status('fakeToken');
        $this->assertFalse($status->isApproved());
    }

    #[Test]
    public function it_can_check_is_rejected_when_details_is_not_approved()
    {
        $this->setBaseMocks();
        $this->requestServiceMock->method('request')
            ->willReturn([
                'expiration_date' => '2021-02-18',
                'details' => [['response_code' => -96, 'status' => 'AUTHORIZED']]
            ]);
        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $status = $transaction->status('fakeToken');
        $this->assertFalse($status->isApproved());
    }

    #[Test]
    public function it_can_validate_token_input()
    {
        $transaction = MallTransaction::buildForIntegration('apiKey', 'commerceCode');

        $this->expectException(InvalidArgumentException::class);
        $transaction->commit('');
    }

    #[Test]
    public function it_can_get_an_refund_response()
    {
        $this->setBaseMocks();
        $this->requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                'type' => 'REVERSED'
            ]);
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION);
        $transaction = new MallTransaction($options, $this->requestServiceMock);
        $refund = $transaction->refund('token', 'buyord', 'commerceCode', 1990);
        $this->assertInstanceOf(MallTransactionRefundResponse::class, $refund);
    }
    #[Test]
    public function it_can_get_an_capture_response()
    {
        $this->setBaseMocks();
        $this->requestServiceMock
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                'authorization_code' => '1234',
                'authorization_date' => '2022-12-12',
                'captured_amount' => 2000,
                'response_code' => 0
            ]);
        $options = new Options('apiKey', 'commerceCode', Options::ENVIRONMENT_INTEGRATION);
        $transaction = new MallTransaction($options, $this->requestServiceMock);
        $refund = $transaction->capture('Commerce', 'token', 'buyOrd', 'auth', 2000);
        $this->assertInstanceOf(MallTransactionCaptureResponse::class, $refund);
    }
}
