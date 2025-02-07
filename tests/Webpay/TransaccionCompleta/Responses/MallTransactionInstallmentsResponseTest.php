<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Transbank\Webpay\TransaccionCompleta\Responses\MallTransactionInstallmentsResponse;

class MallTransactionInstallmentsResponseTest extends TestCase
{
    public function testConstructor()
    {
        $json = [
            'installments_amount' => 1000,
            'id_query_installments' => '123',
            'deferred_periods' => [],
        ];

        $response = new MallTransactionInstallmentsResponse($json);

        $this->assertSame(1000, $response->getInstallmentsAmount());
        $this->assertSame($json['id_query_installments'], $response->idQueryInstallments);
        $this->assertSame($json['deferred_periods'], $response->deferredPeriods);
    }

    public function testGetters()
    {
        $json = [
            'installments_amount' => 2000,
            'id_query_installments' => '456',
            'deferred_periods' => [],
        ];

        $response = new MallTransactionInstallmentsResponse($json);

        $this->assertSame(2000, $response->getInstallmentsAmount());

        $this->assertSame('456', $response->getIdQueryInstallments());

        $this->assertSame([], $response->getDeferredPeriods());
    }
}
