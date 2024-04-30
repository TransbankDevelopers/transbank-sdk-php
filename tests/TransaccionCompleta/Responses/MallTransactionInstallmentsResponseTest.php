<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\MallTransactionInstallmentsResponse;

class MallTransactionInstallmentsResponseTest extends TestCase
{
    public function testConstructor()
    {
        $json = [
            'installments_amount' => 1000,
            'id_query_installments' => '123456',
            'deferred_periods' => 12,
        ];

        $response = new MallTransactionInstallmentsResponse($json);

        $this->assertSame($json['installments_amount'], $response->getInstallmentsAmount());
        $this->assertSame($json['id_query_installments'], $response->idQueryInstallments);
        $this->assertSame($json['deferred_periods'], $response->deferredPeriods);
    }

    public function testGetters()
    {
        $json = [
            'installments_amount' => 2000,
            'id_query_installments' => '123456',
            'deferred_periods' => 12,
        ];

        $response = new MallTransactionInstallmentsResponse($json);

        $this->assertSame(2000, $response->getInstallmentsAmount());

        $this->assertSame('123456', $response->getIdQueryInstallments());

        $this->assertSame(12, $response->getDeferredPeriods());
    }
}
