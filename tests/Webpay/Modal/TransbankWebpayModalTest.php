<?php

use Transbank\Webpay\Modal\Exceptions\TransactionCommitException;
use Transbank\Webpay\Modal\Exceptions\TransactionCreateException;
use Transbank\Webpay\Modal\Exceptions\TransactionRefundException;
use Transbank\Webpay\Modal\Responses\TransactionStatusResponse;
use Transbank\Webpay\Modal\Transaction;
use Transbank\Webpay\Modal\WebpayModal;
use Transbank\Webpay\Options;

class TransbankWebpayModalTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_creates_a_modal_transaction()
    {
        $response = (new Transaction())->create(1500, 'BuyOrder1', 'Session2312');
        $this->assertInstanceOf(\Transbank\Webpay\Modal\Responses\TransactionCreateResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
    }

    /** @test */
    public function it_creates_a_modal_transaction_without_givin_a_session_id()
    {
        $response = (new Transaction())->create(1500, 'BuyOrder1');
        $this->assertInstanceOf(\Transbank\Webpay\Modal\Responses\TransactionCreateResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
    }

    /** @test */
    public function it_fails_when_creates_a_modal_transaction_with_invalid_data()
    {
        $this->expectException(TransactionCreateException::class);
        $response = (new Transaction())->create('hola', '');
    }

    /** @test */
    public function it_get_the_status_of_a_transction()
    {
        $response = (new Transaction())->create(1500, 'BuyOrder1', 'Session2312');
        $status = Transaction::build()->status($response->getToken());
        $this->assertInstanceOf(TransactionStatusResponse::class, $status);
        $this->assertEquals($status->getStatus(), 'INITIALIZED');
    }

    /** @test */
    public function it_fails_when_using_invalid_credentials()
    {
        $this->expectException(TransactionCreateException::class, 'Not Authorized');
        $transaction = new Transaction(Options::forIntegration('commerceCode', 'fakeApiKey'));
        $response = $transaction->create(1500, 'BuyOrder1', 'Session2312');
    }

    /** @test */
    public function it_cannot_commit_a_recently_created_transaction()
    {
        WebpayModal::configureForTesting();
        $this->expectException(TransactionCommitException::class, "Invalid status '0' for transaction while authorizing");
        $response = (new Transaction())->create(1500, 'BuyOrder1', 'Session2312');
        $response = Transaction::build()->commit($response->getToken());
    }

    /** @test */
    public function it_cannot_refund_a_recently_created_transaction()
    {
        $this->expectException(TransactionRefundException::class, 'Transaction is unfinished, it is not possible to refund it yet');
        $response = (new Transaction())->create(1500, 'BuyOrder1', 'Session2312');
        $response = Transaction::build()->refund($response->getToken(), 1500);
    }
}
