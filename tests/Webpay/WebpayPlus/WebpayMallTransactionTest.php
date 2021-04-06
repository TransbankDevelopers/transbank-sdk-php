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
        $this->sessionId = 'some_session_id_'.uniqid();
        $this->buyOrder = '123999555';
        $this->returnUrl = 'https://comercio.cl/callbacks/transaccion_finalizada';
        $this->mockBaseUrl = 'http://mockurl.cl';
    }

    /** @test */
    public function it_uses_the_default_configuration_if_none_given()
    {
        WebpayPlus::reset();
        $transaction = (new MallTransaction());
        $this->assertEquals($transaction->getOptions(), $transaction->getDefaultOptions());
    }

    /** @test */
    public function it_returns_the_default_options()
    {
        $options = MallTransaction::getDefaultOptions();
        $this->assertSame($options->getCommerceCode(), WebpayPlus::DEFAULT_MALL_COMMERCE_CODE);
        $this->assertSame($options->getApiKey(), WebpayPlus::DEFAULT_API_KEY);
        $this->assertSame($options->getIntegrationType(), Options::ENVIRONMENT_INTEGRATION);
    }

    /** @test */
    public function it_can_set_a_specific_option()
    {
        $options = Options::forProduction('597012345678', 'fakeApiKey');

        $transaction = (new MallTransaction($options));
        $this->assertSame($transaction->getOptions(), $options);
    }

    /** @test */
    public function it_can_set_a_specific_option_globally()
    {
        WebpayPlus::configureForProduction('597012345678', 'fakeApiKey');
        $options = WebpayPlus::getOptions();

        $transaction = (new MallTransaction());
        $this->assertSame($transaction->getOptions(), $options);

        WebpayPlus::setOptions(null);
    }

    /** @test */
    public function it_creates_a_transaction()
    {
        $this->setBaseMocks();

        $tokenMock = uniqid();

        $details = [
            'amount'        => $this->amount,
            'commerce_code' => WebpayPlus::DEFAULT_MALL_CHILD_COMMERCE_CODE_1,
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
                    'url'   => 'http://mock.cl/',
                ]
            );

        $transaction = new MallTransaction($this->optionsMock, $this->requestServiceMock);
        $response = $transaction->create($this->buyOrder, $this->sessionId, $this->returnUrl, $details);
        $this->assertInstanceOf(MallTransactionCreateResponse::class, $response);
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
        $this->assertSame($response->getVci(), 'TSY');
        $this->assertSame($response->getSessionId(), 'session1234564');
        $this->assertSame($response->getBuyOrder(), 'OrdenCompra36271');
        $this->assertSame($response->getCardNumber(), '6623');
        $this->assertSame($response->getCardDetail(), ['card_number' => '6623']);
        $this->assertSame($response->getAccountingDate(), '0329');
        $this->assertSame($response->getTransactionDate(), '2021-03-29T04:47:19.885Z');
        $this->assertSame($firstDetail->getResponseCode(), 0);
        $this->assertSame($firstDetail->getStatus(), 'AUTHORIZED');
        $this->assertSame($firstDetail->getAmount(), 1000);
        $this->assertSame($firstDetail->getAuthorizationCode(), '1213');
        $this->assertSame($firstDetail->getPaymentTypeCode(), 'VN');
        $this->assertSame($firstDetail->getInstallmentsNumber(), 0);
        $this->assertSame($firstDetail->getInstallmentsAmount(), null);
        $this->assertSame($firstDetail->getCommerceCode(), '597055555536');
        $this->assertSame($firstDetail->getBuyOrder(), 'OrdenCompraChild_66986_1');
        $this->assertSame($secondDetail->getResponseCode(), 0);
        $this->assertSame($secondDetail->getStatus(), 'AUTHORIZED');
        $this->assertSame($secondDetail->getAmount(), 2000);
        $this->assertSame($secondDetail->getAuthorizationCode(), '1213');
        $this->assertSame($secondDetail->getPaymentTypeCode(), 'VN');
        $this->assertSame($secondDetail->getInstallmentsNumber(), 0);
        $this->assertSame($secondDetail->getInstallmentsAmount(), null);
        $this->assertSame($secondDetail->getCommerceCode(), '597055555537');
        $this->assertSame($secondDetail->getBuyOrder(), 'OrdenCompraChild_66986_2');
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
