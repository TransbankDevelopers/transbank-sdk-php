<?php

class TransbankWebpayModalTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function it_creates_a_modal_transaction()
    {
        $response = \Transbank\Webpay\Modal\Transaction::create( 1500, 'BuyOrder1', 'affsafas');
        $this->assertTrue(true);
    }
}
