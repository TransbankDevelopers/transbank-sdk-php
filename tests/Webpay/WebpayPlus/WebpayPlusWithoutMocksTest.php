<?php

namespace Webpay\WebpayPlus;

use PHPUnit\Framework\TestCase;
use Transbank\Webpay\Options;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Transaction;

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

    protected function setUp(): void
    {
        $this->amount = 1000;
        $this->sessionId = 'some_session_id_'.uniqid();
        $this->buyOrder = '123999555';
        $this->returnUrl = 'https://comercio.cl/callbacks/transaccion_creada_exitosamente';
        $this->mockBaseUrl = 'http://mockurl.cl';
    }

    /** @test */
    public function it_creates_a_real_transaction_without_options()
    {
        $transactionResult = Transaction::build()->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);

        $this->assertNotNull($transactionResult->getToken());
        $this->assertNotNull($transactionResult->getUrl());
    }

    /** @test */
    public function it_creates_a_real_transaction_with_options()
    {
        $options = new Options(WebpayPlus::DEFAULT_API_KEY, WebpayPlus::DEFAULT_COMMERCE_CODE);
        $transaction = (new Transaction());
        $transaction->setOptions($options);
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
        $options = new Options('fakeApiKey', 'fakeCommerceCode');

        $this->expectException(\Exception::class, 'Not Authorized');
        $transaction = (new Transaction());
        $transaction->setOptions($options);
        $transaction->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);
    }

    /** @test */
    public function it_can_get_the_status_of_a_transaction()
    {
        $response = (new Transaction())->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);

        $response = (new Transaction())->status($response->getToken());
        $this->assertEquals('INITIALIZED', $response->getStatus());
        $this->assertEquals($this->amount, $response->getAmount());
        $this->assertEquals($this->buyOrder, $response->getBuyOrder());
        $this->assertEquals($this->sessionId, $response->getSessionId());
    }

    /** @test */
    public function it_can_not_commit_a_recently_created_transaction()
    {
        $response = $this->createTransaction();

        $this->expectException(TransactionCommitException::class, "Invalid status '0' for transaction while authorizing");
        (new Transaction())->commit($response->getToken());
    }

    /** @test */
    public function it_can_not_capture_a_recently_created_transaction()
    {
        WebpayPlus::configureForTestingDeferred();
        $response = $this->createTransaction();
        $this->expectException(TransactionCaptureException::class, 'Transaction not found');
        (new Transaction())->capture($response->getToken(), $this->buyOrder, 'authCode', $this->amount);
    }

    /** @test */
    public function it_can_not_capture_a_transaction_with_simultaneous_capture_commerce_code()
    {
        WebpayPlus::configureForTesting();
        $response = $this->createTransaction();
        $this->expectException(TransactionCaptureException::class, 'Operation not allowed');
        WebpayPlus::transaction()->capture($response->getToken(), $this->buyOrder, 'authCode', $this->amount);

        $this->assertTrue(true);
    }

    /**
     * @throws WebpayPlus\Exceptions\TransactionCreateException
     *
     * @return \Transbank\Webpay\WebpayPlus\Responses\TransactionCreateResponse
     */
    public function createTransaction()
    {
        $response = (new Transaction())->create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);

        return $response;
    }
}
