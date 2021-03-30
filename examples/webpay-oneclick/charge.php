<?php

include_once '../../vendor/autoload.php';

$webpay = \Transbank\Sdk\Transbank::make()->oneclickMall();

$result = $webpay->authorize(
    tbkUser: $_POST['tbkUser'],
    username: $_POST['username'],
    buyOrder: $buyOrder = date('YmdHis') . '000',
    details: $detail = [
        'commerce_code' => $childCommerceCode = '597055555542',
        'buy_order' => $childBuyOrder = 'ordenCompra123445',
        'amount' => $amount = 1000,
        'installments_number' => 5
    ]
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
        <h4>Cargo enviado:</h4>
        <ul>
            <li><strong>Nombre</strong>: <?php echo $_POST['username'] ?></li>
            <li><strong>Token</strong>: <?php echo $_POST['tbkUser'] ?></li>
            <li><strong>Orden</strong>: <?php echo $buyOrder ?></li>
            <li><strong>Detalles</strong>
                <ul>
                <li><strong>Código Comercio</strong>: <?php echo $detail['commerce_code'] ?></li>
                <li><strong>Orden</strong>: <?php echo $detail['buy_order'] ?></li>
                <li><strong>Monto</strong>: <?php echo $detail['amount'] ?></li>
                <li><strong>Cuotas</strong>: <?php echo $detail['installments_number'] ?></li>
                </ul>
            </li>
        </ul>
    </div>
    <form action="reverse.php" method="POST">
        <input type="hidden" name="buyOrder"
               value="<?php echo $buyOrder ?>">
        <input type="hidden" name="tbkUser"
               value="<?php echo $_POST['tbkUser'] ?>">
        <input type="hidden" name="username"
               value="<?php echo $_POST['username'] ?>">
        <input type="hidden" name="childCommerceCode"
               value="<?php echo $childCommerceCode ?>">
        <input type="hidden" name="childBuyOrder"
               value="<?php echo $childBuyOrder ?>">
        <input type="hidden" name="amount"
               value="<?php echo $amount ?>">
        <button type="submit" class="btn btn-lg btn-primary mb-3">Revertir Cargo</button>
    </form>
</body>
</html>
