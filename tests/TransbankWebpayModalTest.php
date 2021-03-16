<?php

use Transbank\Webpay\Modal\Responses\TransactionStatusResponse;
use Transbank\Webpay\Modal\Transaction;

class TransbankWebpayModalTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_creates_a_modal_transaction()
    {
        $response = Transaction::create(1500, 'BuyOrder1', 'Session2312');
        $this->assertInstanceOf(\Transbank\Webpay\Modal\Responses\TransactionCreateResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
    }

    /** @test */
    public function it_creates_a_modal_transaction_withoud_givin_a_session_id()
    {
        $response = Transaction::create(1500, 'BuyOrder1');
        $this->assertInstanceOf(\Transbank\Webpay\Modal\Responses\TransactionCreateResponse::class, $response);
        $this->assertNotEmpty($response->getToken());
    }

    /** @test */
    public function it_fails_when_creates_a_modal_transaction_with_invalid_data()
    {
        $this->setExpectedException(\Transbank\Webpay\Modal\Exceptions\TransactionCreateException::class);
        $response = Transaction::create('hola', '');
    }

    /** @test */
    public function it_get_the_status_of_a_transction()
    {
        $response = Transaction::create(1500, 'BuyOrder1', 'Session2312');
        $status = Transaction::status($response->getToken());
        $this->assertInstanceOf(TransactionStatusResponse::class, $status);
        $this->assertEquals($status->getStatus(), 'INITIALIZED');
    }

    /** @test */
    public function it_fails_when_using_invalid_credentials()
    {
        \Transbank\Webpay\Modal\WebpayModal::setApiKey('sfaffasfa');
        \Transbank\Webpay\Modal\WebpayModal::setCommerceCode('1233');

        $this->setExpectedException(\Transbank\Webpay\Modal\Exceptions\TransactionCreateException::class, 'Not Authorized');
        $response = Transaction::create(1500, 'BuyOrder1', 'Session2312');
    }

    /** @test */
    public function it_cannot_commit_a_recently_created_transaction()
    {
        \Transbank\Webpay\Modal\WebpayModal::configureForTesting();
        $this->setExpectedException(\Transbank\Webpay\Modal\Exceptions\TransactionCommitException::class, "Invalid status '0' for transaction while authorizing");
        $response = Transaction::create(1500, 'BuyOrder1', 'Session2312');
        $response = Transaction::commit($response->getToken());
    }

    /** @test */
    public function it_cannot_refund_a_recently_created_transaction()
    {
        $this->setExpectedException(\Transbank\Webpay\Modal\Exceptions\TransactionRefundException::class, "Transaction is unfinished, it is not possible to refund it yet");
        $response = Transaction::create(1500, 'BuyOrder1', 'Session2312');
        $response = Transaction::refund($response->getToken(), 1500);
    }
}
