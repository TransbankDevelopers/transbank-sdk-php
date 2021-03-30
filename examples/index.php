<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ejemplos de integración de Servicios Transbank</title>
    <?php include __DIR__ . '/_master/header.php' ?>
</head>
<body>

<div class="container">
    <h1 class="mb-3">Ejemplos de integración de Servicios Transbank</h1>
    <div class="alert alert-info small">
        <i class="fas fa-info-circle"></i> Todos estos ejemplos usan las credenciales de integración suministradas por Transbank, así que no es necesario hacer nada más que probar cómo funcionan las transacciones.
    </div>
    <div class="list-group">
        <a href="webpay-normal/start.php" class="list-group-item list-group-item-action">
            <h3>Webpay Plus Normal</h3>
            <p>Creación</p>
        </a>
        <a href="webpay-mall-normal/start.php" class="list-group-item list-group-item-action">
            <h3>Webpay Plus Mall Normal</h3>
            <p>Creación</p>
        </a>
        <a href="webpay-oneclick/start.php" class="list-group-item list-group-item-action">
            <h3>Webpay Oneclick</h3>
            <p>Registro, Cargo, Revertir y Dar de baja.</p>
        </a>
    </div>
</div>
</body>
</html>
