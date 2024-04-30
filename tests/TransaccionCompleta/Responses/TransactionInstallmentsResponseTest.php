<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\TransactionInstallmentsResponse;

class TransactionInstallmentsResponseTest extends TestCase
{

    protected $json;
    protected $transactionInstallmentsResponse;

    public function setUp(): void
    {
        $this->json = [
            'installments_amount' => 1000,
            'id_query_installments' => '123456',
            'deferred_periods' => ['period1', 'period2'],
        ];

        $this->transactionInstallmentsResponse = new TransactionInstallmentsResponse($this->json);
    }
    public function testGetIdQueryInstallments()
    {
        $this->assertSame('123456', $this->transactionInstallmentsResponse->idQueryInstallments);
    }

    public function testGetDeferredPeriods()
    {
        $this->assertSame(['period1', 'period2'], $this->transactionInstallmentsResponse->getDeferredPeriods());
    }

    public function testGetInstallmentsAmount()
    {
        $this->assertSame(1000, $this->transactionInstallmentsResponse->getInstallmentsAmount());
    }
}
