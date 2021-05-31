<?php

require '../../vendor/autoload.php';

use Transbank\TransaccionCompleta\MallTransaction;
use Transbank\TransaccionCompleta\TransaccionCompleta;

TransaccionCompleta::configureForTestingMallDeferred();
$firstStoreCommerceCode = TransaccionCompleta::DEFAULT_MALL_DEFERRED_CHILD_COMMERCE_CODE_1;
$secondStoreCommerceCode = TransaccionCompleta::DEFAULT_MALL_DEFERRED_CHILD_COMMERCE_CODE_2;
$firstBuyOrder = uniqid();
$secondBuyOrder = uniqid();
/*
|--------------------------------------------------------------------------
| Create mall transaction
|--------------------------------------------------------------------------
*/
$transaction_details = [
    [
        'amount'        => 10000,
        'commerce_code' => $firstStoreCommerceCode,
        'buy_order'     => $firstBuyOrder,
    ],
    [
        'amount'        => 12000,
        'commerce_code' => $secondStoreCommerceCode,
        'buy_order'     => $secondBuyOrder,
    ],
];
$cardNumber = '4051885600446623';
$cardExpirationDate = '22/12';
$cvv = '123';

$transaction = new MallTransaction();
$response = $transaction->create(
    'buyOrder1',
    'sessionId',
    $cardNumber,                       // 4239000000000000
    $cardExpirationDate,              // 22/10
    $transaction_details,
    $cvv
);

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
        'commerce_code'       => $firstStoreCommerceCode,
        'buy_order'           => $firstBuyOrder,
        'installments_number' => 2,
    ],
    [
        'commerce_code'       => $secondStoreCommerceCode,
        'buy_order'           => $secondBuyOrder,
        'installments_number' => 2,
    ],
];

echo 'Installments: '."\n";
$response = $transaction->installments($token, $installments_details);
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
        'commerce_code'         => $firstStoreCommerceCode,
        'buy_order'             => $firstBuyOrder,
        'id_query_installments' => $firstInstallmentResponse->getIdQueryInstallments(),
        'deferred_period_index' => null,
        'grace_period'          => false,
    ],
    [
        'commerce_code'         => $secondStoreCommerceCode,
        'buy_order'             => $secondBuyOrder,
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

$secondDetail = $response->getDetails()[0];
$authorizationCode = $secondDetail->getAuthorizationCode();
/*
|--------------------------------------------------------------------------
| Refund
|--------------------------------------------------------------------------
*/

$response = $transaction->refund($token, $firstBuyOrder, $firstStoreCommerceCode, 10000);

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

/*
|--------------------------------------------------------------------------
| Captura
|--------------------------------------------------------------------------
*/

$response = $transaction->capture(
    $token,
    $secondStoreCommerceCode,
    $secondBuyOrder,
    $authorizationCode,
    10000
);

echo 'Capture: '."\n";
print_r($response);

/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

$response = $transaction->status($token);

echo 'Status '."\n";
print_r($response);
