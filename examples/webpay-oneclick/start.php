<?php

include_once '../load.php';

$webpay = \Transbank\Sdk\Transbank::make()->oneclickMall();

$result = $webpay->start(
    $username = 'username_' . date('Y-m-d_H-i-s'),
    $email = 'email@commerceemaill.com',
    $responseUrl = currentUrlPath('response.php')
);

// Guardemos el nombre del usuario
file_put_contents(
    'username.txt',
    json_encode(
        [
            'username' => $username,
            'email' => 'email@commerceemaill.com',
        ]
    )
);

// HTML para redirigir la prueba
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
        <h4>Información de subscripción enviada</h4>
        <ul>
            <li><strong>Nombre</strong>: <?php echo $username ?></li>
            <li><strong>Email</strong>: <?php echo $email ?></li>
            <li><strong>Url</strong>: <?php echo $responseUrl ?></li>
        </ul>
    </div>

    <div class="card card-body mb-5">
        <form id="redirect" action="<?php
        echo $result->getUrl() ?>" method="POST">
            <input type="hidden" name="TBK_TOKEN"
                   value="<?php echo htmlspecialchars($result->getToken(), ENT_HTML5) ?>">
            <button type="submit" class="btn btn-lg btn-primary mb-3">Registrar</button>
        </form>
    </div>
</div>
</body>
</html>
