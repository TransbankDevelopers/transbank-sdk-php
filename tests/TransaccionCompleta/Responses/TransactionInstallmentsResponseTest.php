<?php

use PHPUnit\Framework\TestCase;
use Transbank\TransaccionCompleta\Responses\TransactionInstallmentsResponse;

class TransactionInstallmentsResponseTest extends TestCase
{
    public function testSetIdQueryInstallments()
    {
        $transactionInstallmentsResponse = new TransactionInstallmentsResponse(200);
        $idQueryInstallments = '123456';

        $transactionInstallmentsResponse->setIdQueryInstallments($idQueryInstallments);

        $this->assertSame($idQueryInstallments, $transactionInstallmentsResponse->idQueryInstallments);
    }

    public function testGetDeferredPeriods()
    {
        $transactionInstallmentsResponse = new TransactionInstallmentsResponse(200);
        $deferredPeriods = ['period1', 'period2'];

        // Assuming you have a setter for deferredPeriods
        $transactionInstallmentsResponse->setDeferredPeriods($deferredPeriods);

        $this->assertSame($deferredPeriods, $transactionInstallmentsResponse->getDeferredPeriods());
    }

    public function testSetInstallmentsAmount()
    {
        $transactionInstallmentsResponse = new TransactionInstallmentsResponse(200);
        $installmentsAmount = 1000;

        $transactionInstallmentsResponse->setInstallmentsAmount($installmentsAmount);

        $this->assertSame($installmentsAmount, $transactionInstallmentsResponse->getInstallmentsAmount());
    }
}
