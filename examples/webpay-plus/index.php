<?php

require '../../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Inicializamos el objeto Transaction
|--------------------------------------------------------------------------
*/
$transaction = new \Transbank\Webpay\WebpayPlus\Transaction();

// Por simplicidad de este ejemplo, este es nuestro "controlador" que define que vamos a hacer dependiendo del parametro ?action= de la URL.
$action = $_GET['action'] ?? null;
if (!$action) {
    exit('Debe indicar la acción a realizar');
}

/*
|--------------------------------------------------------------------------
| Crear transacción
|--------------------------------------------------------------------------
/ Apenas entramos esta página, con fines demostrativos,
*/
if ($_GET['action'] === 'create') {
    $createResponse = $transaction->create('buyOrder123', uniqid(), 1500, 'http://localhost:8000/webpay-plus/index.php?action=result');

    // Acá guardar el token recibido ($createResponse->getToken()) en tu base de datos asociado a la orden o
    // lo que se esté pagando en tu sistema

    //Redirigimos al formulario de Webpay por GET, enviando a la URL recibida con el token recibido.
    $redirectUrl = $createResponse->getUrl().'?token_ws='.$createResponse->getToken();
    header('Location: '.$redirectUrl, true, 302);
    exit;
}
/*
|--------------------------------------------------------------------------
| Confirmar transacción
|--------------------------------------------------------------------------
/ Esto se debería ejecutar cuando el usario finaliza el proceso de pago en el formulario de webpay.
*/
if ($_GET['action'] === 'result') {
    if (userAbortedOnWebpayForm()) {
        cancelOrder();
        exit('Has cancelado la transacción en el formulario de pago. Intenta nuevamente');
    }
    if (anErrorOcurredOnWebpayForm()) {
        cancelOrder();
        exit('Al parecer ocurrió un error en el formulario de pago. Intenta nuevamente');
    }
    if (theUserWasRedirectedBecauseWasIdleFor10MinutesOnWebapayForm()) {
        cancelOrder();
        exit('Superaste el tiempo máximo que puedes estar en el formulario de pago (10 minutos). La transacción fue cancelada por Webpay. ');
    }
    //Por último, verificamos que solo tengamos un token_ws. Si no es así, es porque algo extraño ocurre.
    if (!isANormalPaymentFlow()) { // Notar que dice ! al principio.
        cancelOrder();
        exit('En este punto, si NO es un flujo de pago normal es porque hay algo extraño y es mejor abortar. Quizás alguien intenta llamar a esta URL directamente o algo así...');
    }

    // Acá ya estamos seguros de que tenemos un flujo de pago normal. Si no, habría "muerto" en los checks anteriores.
    $token = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null; // Obtener el token de un flujo normal
    $response = $transaction->commit($token);

    if ($response->isApproved()) {
        //Si el pago está aprobado (responseCode == 0 && status === 'AUTHORIZED') entonces aprobamos nuestra compra
        // Código para aprobar compra acá
        approveOrder($response);
    } else {
        cancelOrder();
    }

    return;
}

function cancelOrder($response = null)
{
    // Acá has lo que tangas que hacer para marcar la orden como fallida o cancelada
    if ($response) {
        echo '<pre>'.print_r($response, true).'</pre>';
    }
    echo 'La orden ha sido RECHAZADA';
}

function approveOrder($response)
{
    // Acá has lo que tangas que hacer para marcar la orden como aprobada o finalizada o lo que necesites en tu negocio.,
    echo 'La orden ha sido APROBADA';
    echo '<pre>'.print_r($response, true).'</pre>';
}

function userAbortedOnWebpayForm()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;

    // Si viene TBK_TOKEN, TBK_ORDEN_COMPRA y TBK_ID_SESION es porque el usuario abortó el pago
    return $tbkToken && $ordenCompra && $idSesion && !$tokenWs;
}

function anErrorOcurredOnWebpayForm()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;

    // Si viene token_ws, TBK_TOKEN, TBK_ORDEN_COMPRA y TBK_ID_SESION es porque ocurrió un error en el formulario de pago
    return $tokenWs && $ordenCompra && $idSesion && $tbkToken;
}

function theUserWasRedirectedBecauseWasIdleFor10MinutesOnWebapayForm()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;

    // Si viene solo TBK_ORDEN_COMPRA y TBK_ID_SESION es porque el usuario estuvo 10 minutos sin hacer nada en el
    // formulario de pago y se canceló la transacción automáticamente (por timeout)
    return $ordenCompra && $idSesion && !$tokenWs && !$tbkToken;
}

function isANormalPaymentFlow()
{
    $tokenWs = $_GET['token_ws'] ?? $_POST['token_ws'] ?? null;
    $tbkToken = $_GET['TBK_TOKEN'] ?? $_POST['TBK_TOKEN'] ?? null;
    $ordenCompra = $_GET['TBK_ORDEN_COMPRA'] ?? $_POST['TBK_ORDEN_COMPRA'] ?? null;
    $idSesion = $_GET['TBK_ID_SESION'] ?? $_POST['TBK_ID_SESION'] ?? null;

    // Si viene solo token_ws es porque es un flujo de pago normal
    return $tokenWs && !$ordenCompra && !$idSesion && !$tbkToken;
}
