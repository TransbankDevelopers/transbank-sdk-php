<?php

include_once '../load.php';

$webpay = \Transbank\Sdk\Transbank::make()->webpayMall();

$result = $webpay->commit($_POST['token_ws']);

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado Webpay</title>
    <?php include __DIR__ . '/../_master/header.php' ?>
</head>
<body>
<div class="container">
    <div class="card card-body mb-5">
        <h4>Recepción desde Webpay</h4>
        <strong>Token:</strong> <?php echo $_POST['token_ws'] ?>
        <pre><?php print_r($result) ?></pre>

        <?php if ($result->isSuccessful()) { ?>
            <div class="alert alert-success">La transacción fue aceptada.</div>
        <?php } else { ?>
            <div class="alert alert-danger">La transacción fue rechazada.</div>
        <?php } ?>
    </div>
    <div class="text-left">
        <a href="<?php echo currentUrlPath('../index.php') ?>" target="_self" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver al índice
        </a>
    </div>
</div>
</body>
</html>
