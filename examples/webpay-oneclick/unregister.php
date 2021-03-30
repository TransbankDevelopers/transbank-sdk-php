<?php

include_once '../../vendor/autoload.php';

$webpay = \Transbank\Sdk\Transbank::make()->oneclickMall();

$webpay->delete($_POST['tbkUser'], $_POST['username']);

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
        <h4>Subscripción eliminada</h4>
    </div>
    <a href="start.php" class="btn btn-lg btn-primary mb-3">Comenzar de nuevo</a>
</div>
</body>
</html>
