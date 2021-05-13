<?php

require '../../vendor/autoload.php';

use Transbank\TransaccionCompleta\MallTransaction;
use Transbank\TransaccionCompleta\TransaccionCompleta;

/*
|--------------------------------------------------------------------------
| Create mall transaction
|--------------------------------------------------------------------------
*/
$transaction_details = [
    [
        'amount'        => 10000,
        'commerce_code' => TransaccionCompleta::DEFAULT_MALL_CHILD_COMMERCE_CODE_1,
        'buy_order'     => '123456789',
    ],
    [
        'amount'        => 12000,
        'commerce_code' => TransaccionCompleta::DEFAULT_MALL_CHILD_COMMERCE_CODE_2,
        'buy_order'     => '123456790',
    ],
];
$cardNumber = '4051885600446623';
$cardExpirationDate = '22/12';
$cvv = '123';

$transaction = new MallTransaction();
$response = $transaction->create('buyOrder1',                         // ordenCompra12345678
    'sessionId',                        // sesion1234564
    $cardNumber,                       // 4239000000000000
    $cardExpirationDate,              // 22/10
    $transaction_details, $cvv);

echo 'Create transaction'."\n";
print_r($response);

$token = $response->getToken();
/*
|--------------------------------------------------------------------------
|  Installments
|--------------------------------------------------------------------------
*/

$installments_details = [
    [
        'commerce_code'       => TransaccionCompleta::DEFAULT_MALL_CHILD_COMMERCE_CODE_1,
        'buy_order'           => '123456789',
        'installments_number' => 2,
    ],
    [
        'commerce_code'       => TransaccionCompleta::DEFAULT_MALL_CHILD_COMMERCE_CODE_2,
        'buy_order'           => '123456790',
        'installments_number' => 2,
    ],
];

$response = $transaction->installments($token, $installments_details);
echo 'Installments'."\n";
print_r($response);

$firstInstallmentResponse = $response[0];
$secondInstallmentResponse = $response[0];
$firstInstallmentResponse->getInstallmentsAmount();
$firstInstallmentResponse->getIdQueryInstallments();
$deferredPeriods = $firstInstallmentResponse->getDeferredPeriods();
if (isset($deferredPeriods[0])) {
    $deferredPeriod = $deferredPeriods[0];
    $deferredPeriod->getAmount();
    $deferredPeriod->getPeriod();
}

/*
|--------------------------------------------------------------------------
| Commit
|--------------------------------------------------------------------------
*/

$details = [
    [
        'commerce_code'         => TransaccionCompleta::DEFAULT_MALL_CHILD_COMMERCE_CODE_1,
        'buy_order'             => '123456789',
        'id_query_installments' => $firstInstallmentResponse->getIdQueryInstallments(),
        'deferred_period_index' => null,
        'grace_period'          => false,
    ],
    [
        'commerce_code'         => TransaccionCompleta::DEFAULT_MALL_CHILD_COMMERCE_CODE_2,
        'buy_order'             => '123456790',
        'id_query_installments' => $secondInstallmentResponse->getIdQueryInstallments(),
        'deferred_period_index' => null,
        'grace_period'          => false,
    ],
];

$response = $transaction->commit($token, $details);
echo 'Commit '."\n";
print_r($response);

$response->getBuyOrder();
$response->getCardNumber();
$response->getAccountingDate();
$response->getTransactionDate();
$detail = $response->getDetails()[0];
$detail->getAuthorizationCode();
$detail->getPaymentTypeCode();
$detail->getResponseCode();
$detail->getInstallmentsAmount();
$detail->getInstallmentsNumber();
$detail->getAmount();
$detail->getCommerceCode();
$detail->getBuyOrder();
$detail->getStatus();

/*
|--------------------------------------------------------------------------
| Refund
|--------------------------------------------------------------------------
*/

$response = $transaction->refund($token, '123456789', TransaccionCompleta::DEFAULT_MALL_CHILD_COMMERCE_CODE_1, 10000);

echo 'Refund '."\n";
print_r($response);

$response->getType();
$response->getAuthorizationCode();
$response->getAuthorizationDate();
$response->getNullifiedAmount();
$response->getBalance();
$response->getResponseCode();

/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

$response = $transaction->status($token);

echo 'Status '."\n";
print_r($response);
