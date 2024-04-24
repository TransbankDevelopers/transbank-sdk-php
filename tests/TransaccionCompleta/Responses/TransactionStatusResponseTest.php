<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\TransactionStatusResponse;

class TransactionStatusResponseTest extends TestCase
{
    /** @test */
    public function it_can_set_and_get_prepaid_balance()
    {
        $response = new TransactionStatusResponse(200);
        $response->setPrepaidBalance(100.00);

        $this->assertSame(100.00, $response->getPrepaidBalance());
    }

    public function it_can_set_and_get_vci()
    {
        $response = new TransactionStatusResponse(200);
        $response->setVci('Some VCI');

        $this->assertSame('Some VCI', $response->getVci());
    }
}
