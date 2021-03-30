<?php

include_once '../load.php';

$webpay = \Transbank\Sdk\Transbank::make()->webpay();

$response = $webpay->create(
    buyOrder: $buyOrder = 'myOrder-' . rand(1,9999),
    amount: $amount = rand(1000, 29999),
    returnUrl: currentUrlPath('final.php'),
    sessionId: $sessionId = 'test_Session_id'
);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Conectando con Webpay...</title>
    <?php include __DIR__ . '/../_master/header.php' ?>
</head>
<body>
<div class="container">
    <div class="card card-body mb-5">
        <h4>Información de pago enviado</h4>
        <ul>
            <li><strong>Orden</strong>: <?php echo $buyOrder ?></li>
            <li><strong>Monto</strong>: <?php echo $amount ?></li>
            <li><strong>Retorno</strong>: <?php echo currentUrlPath('final.php') ?></li>
        </ul>
    </div>
    <form id="redirect" action="<?php echo $response->getUrl() ?>" method="POST">
        <input type="hidden" name="token_ws" value="<?php echo htmlspecialchars($response->getToken(), ENT_HTML5) ?>">
        <div class="text-center">
            <button type="submit" class="btn btn-lg btn-primary mb-3">
                Ir a Webpay <i class="fas fa-arrow-right"></i>
            </button>
            <div class="small text-black-50">
                Esto hará una petición HTTP POST hacia Transbank.
            </div>
        </div>
    </form>
</body>
</html>
