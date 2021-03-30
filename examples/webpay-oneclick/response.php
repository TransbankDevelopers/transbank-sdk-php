<?php

include_once '../load.php';

$webpay = \Transbank\Sdk\Transbank::make()->oneclickMall();

$response = $webpay->finish($_POST['TBK_TOKEN']);

$data = json_decode(file_get_contents('username.txt'), true);

$data['tbk_user'] = $response->getTbkUser();

file_put_contents('username.txt', json_encode($data))

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
        <h4>Información de subscripción recibida:</h4>
        <ul>
            <li><strong>Nombre</strong>: <?php echo $data['username'] ?></li>
            <li><strong>Email</strong>: <?php echo $data['email'] ?></li>
            <li><strong>Token</strong>: <?php echo $data['tbk_user'] ?></li>
        </ul>
    </div>
    <form action="charge.php" method="POST">
        <input type="hidden" name="tbkUser"
               value="<?php echo $data['tbk_user'] ?>">
        <input type="hidden" name="username"
               value="<?php echo $data['username'] ?>">
        <button type="submit" class="btn btn-lg btn-primary mb-3">Cargar un monto a la tarjeta</button>
    </form>
</div>
</body>
</html>
