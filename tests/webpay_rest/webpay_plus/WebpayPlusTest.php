<?php

namespace Transbank\Webpay;


use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCaptureException;
use Transbank\Webpay\WebpayPlus\Exceptions\TransactionCommitException;
use Transbank\Webpay\WebpayPlus\Transaction;

class WebpayPlusTest extends \PHPUnit_Framework_TestCase
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

    protected function setup()
    {
        $this->amount = 1000;
        $this->sessionId = "some_session_id_".uniqid();
        $this->buyOrder = "123999555";
        $this->returnUrl = "https://comercio.cl/callbacks/transaccion_creada_exitosamente";
    }

    public function testCreateATransactionWithoutOptions()
    {
        $transactionResult = Transaction::create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl
        );

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
        $options = new Options(
            'fakeApiKey',
            'fakeCommerceCode'
        );

        $this->setExpectedException(\Exception::class, 'Not Authorized');
        Transaction::create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl,
            $options
        );
    }

    /** @test */
    public function it_can_get_the_status_of_a_transaction()
    {
        $response = Transaction::create(
            $this->buyOrder,
            $this->sessionId,
            $this->amount,
            $this->returnUrl
        );

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

        $this->setExpectedException(TransactionCommitException::class, "Invalid status '0' for transaction while authorizing");
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
     */
    public function createTransaction()
    {
        $response = Transaction::create($this->buyOrder, $this->sessionId, $this->amount, $this->returnUrl);

        return $response;
    }
}
