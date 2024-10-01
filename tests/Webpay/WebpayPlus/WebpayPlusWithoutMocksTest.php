<?php

namespace Webpay\WebpayPlus;

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCreateException;
use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\WebpayPlus\Responses\TransactionStatusResponse;
use Transbank\Utils\Curl\HttpCurlClient;
use Transbank\Utils\HttpClientRequestService;

class WebpayPlusWithoutMocksTest extends TestCase
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
     * @var Options
     */
    public $options;

    public $clientRequestService;

    protected function setUp(): void
    {
        $this->amount = 1000;
        $this->sessionId = 'some_session_id_' . uniqid();
        $this->buyOrder = '123999555';
        $this->returnUrl = 'https://comercio.cl/callbacks/transaccion_creada_exitosamente';
        $this->mockBaseUrl = 'https://mockurl.cl';
        $this->options = new Options(
            WebpayPlus::INTEGRATION_API_KEY,
            WebpayPlus::INTEGRATION_COMMERCE_CODE,
            Options::ENVIRONMENT_INTEGRATION
        );
        $httpClient = new HttpCurlClient();
        $this->clientRequestService = new HttpClientRequestService($httpClient);
    }

    /** @test */
    public function it_creates_a_real_transaction_with_options()
    {
        $transaction = (new Transaction($this->options, $this->clientRequestService));
        $transactionResult = $transaction->create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl
        );

        $this->assertNotNull($transactionResult->getToken());
        $this->assertNotNull($transactionResult->getUrl());
    }

    public function testCreateTransactionWithIncorrectCredentialsShouldFail()
    {
        $options = new Options('fakeApiKey', 'fakeCommerceCode', Options::ENVIRONMENT_INTEGRATION);

        $this->expectException(TransactionCreateException::class);
        $this->expectExceptionMessage('Not Authorized');
        $transaction = (new Transaction($options, $this->clientRequestService));
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }

    /** @test */
    public function it_can_get_the_status_of_a_transaction()
    {
        $jsonResponse = [
            'status' => 'INITIALIZED',
            'amount' => 1000,
            'buy_order' => 'orderOf1000',
            'sessionId' => 'sessionOf1000',
            'vci' => 'TSY',
            'session_id' => 'sessionOf1000',
            'card_detail' => [
                'card_number' => '6623',
            ],
            'accounting_date' => '0624',
            'transaction_date' => '2020-06-24T17:52:31.000Z',
            'authorization_code' => '1000',
            'payment_type_code' => 'VN',
            'response_code' => 0,
            'installments_number' => 4,
            'installments_amount' => 250,
        ];

        $response = new TransactionStatusResponse($jsonResponse);
        $this->assertEquals('INITIALIZED', $response->getStatus());
    }

    /** @test */
    public function it_can_not_commit_a_recently_created_transaction()
    {
        $response = (new Transaction($this->options, $this->clientRequestService))->create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl
        );

        $this->expectException(TransactionCommitException::class);
        $this->expectExceptionMessage("Invalid status '0' for transaction while authorizing");
        (new Transaction($this->options))->commit($response->getToken());
    }

    /** @test */
    public function it_can_not_capture_a_recently_created_transaction()
    {
        $deferredOptions = new Options(
            WebpayPlus::INTEGRATION_API_KEY,
            WebpayPlus::INTEGRATION_DEFERRED_COMMERCE_CODE,
            Options::ENVIRONMENT_INTEGRATION
        );
        $response = (new Transaction($deferredOptions, $this->clientRequestService))->create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl
        );
        $this->expectException(TransactionCaptureException::class);
        $this->expectExceptionMessage('Transaction not found');
        (new Transaction($deferredOptions))->capture($response->getToken(), $this->buyOrder, 'authCode', $this->amount);
    }

    /** @test */
    public function it_can_not_capture_a_transaction_with_simultaneous_capture_commerce_code()
    {
        $transaction = new Transaction(new Options(
            WebpayPlus::INTEGRATION_API_KEY,
            WebpayPlus::INTEGRATION_COMMERCE_CODE,
            Options::ENVIRONMENT_INTEGRATION
        ), $this->clientRequestService);
        $response = $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        $this->expectException(TransactionCaptureException::class);
        $this->expectExceptionMessage('Operation not allowed');
        (new Transaction($this->options))->capture($response->getToken(), $this->buyOrder, 'authCode', $this->amount);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_returns_a_card_number_in_null_when_it_not_exists()
    {
        $transaction = new Transaction($this->options, $this->clientRequestService);
        $createResponse = $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
        $statusResponse = $transaction->status($createResponse->getToken());

        $this->assertEquals(TransactionStatusResponse::class, get_class($statusResponse));
        $this->assertEquals(null, $statusResponse->getCardNumber());
    }
}
