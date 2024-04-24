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

    public function testGettersAndSetters()
    {
        $response = new MallTransactionInstallmentsResponse([]);

        $response->setInstallmentsAmount(2000);
        $this->assertSame(2000, $response->getInstallmentsAmount());

        $response->setIdQueryInstallments('654321');
        $this->assertSame('654321', $response->getIdQueryInstallments());

        $response->setDeferredPeriods(24);
        $this->assertSame(24, $response->getDeferredPeriods());
    }
}
