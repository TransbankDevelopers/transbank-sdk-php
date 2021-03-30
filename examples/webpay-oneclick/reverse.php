<?php

include_once '../../vendor/autoload.php';

$webpay = \Transbank\Sdk\Transbank::make()->oneclickMall();

$result = $webpay->refund(
    buyOrder: $_POST['buyOrder'],
    childCommerceCode: $_POST['childCommerceCode'],
    childBuyOrder: $_POST['childBuyOrder'],
    amount: $_POST['amount'],
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
        <h4>Respuesta de Transbank:</h4>
        <pre>
            <?php print_r($result) ?>
        </pre>
    </div>
    <form action="unregister.php" method="POST">
        <input type="hidden" name="tbkUser"
               value="<?php echo $_POST['tbkUser'] ?>">
        <input type="hidden" name="username"
               value="<?php echo $_POST['username'] ?>">
        <button type="submit" class="btn btn-lg btn-primary mb-3">Eliminar subscripción</button>
    </form>
</div>
</body>
</html>
