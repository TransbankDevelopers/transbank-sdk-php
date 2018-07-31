# Transbank OnePay SDK

Requisitos:

- PHP 5.5+

- ext-curl
- ext-json
- ext-mbstring

Para usar el SDK en tu proyecto puedes usar Composer, por añadiendo el repositorio a tu composer.json: 

```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/continuum/transbank-onepay-sdk-php"
        }
    ]
```

y luego requiriendo el SDK como dependencia:
```
    "require": {
        "transbank-onepay-sdk-php": "dev-master"
    }
```

O, si no deseas usar Composer, puedes descargar el código del SDK desde el repositorio y requerirlo directamente:
```
require_once('/directorio/del/sdk/init.php');
```

Luego, para usar el SDK en tu código:

# Crear una transacción
```
use Transbank;
use Transbank\OnePay;
use Transbank\ShoppingCart;
use Transbank\Item;
use Transbank\Transaction;
use Transbank\Refund;

OnePay::setApiKey('tu-api-key');
OnePay::setSharedSecret('tu-shared-secret');
$objeto1 = ["amount" => 15000, "quantity" => 1, "description" => 'Zapatos deportivos'];
$objeto2 = ["amount" => 5000, "quantity" => 3, "description" => 'Calcetines'];

$losObjetos = ["items" => [$objeto1, $objeto2]];

$carro = ShoppingCart::fromJSON($losObjetos)

# description, quantity, amount;
$tercerObjeto = new Item('Pelota de futbol', 1, 20000); 
$carro->add($tercerObjeto);

$transaction = Transaction::create($carro);

# Retorna un objeto TransactionCreateResponse con getters (getNombreAtributo) y setters(setNombreAtributo) para:

    responseCode: Resultado de la creación de la Transacción
    description: Descripción del responseCode
    occ: Número orden de compra comercio
    ott: Versión corta de OTT, usada en la app de OnePay (se puede ingresar el OTT en vez de scanear el QR)
    signature: Firma para verificación de datos
    externalUniqueNumber: Valor usado por el comercio para identificar la transacción
    issuedAt: Momento de creación en UNIX time de la transacción
    qrCodeAsBase64: Código QR en base64. Sirve para que el usuario de OnePay pueda scanearlo con la app para   realizar el pago

# Adicionalmente, implementa JsonSerializable, por lo tanto:

json_encode($transaction);

# Retorna un JSON con la siguiente forma:

{
    "responseCode": "OK",
    "description": "OK",
    "occ": "1807216892091979",
    "ott": 51435450,
    "signature": "i1xFsNiky1VrEoXWUWXqGh9R4yg1/rfZhczEChhwHEU=",
    "externalUniqueNumber": "1532103675510",
    "issuedAt": 1532103850,
    "qrCodeAsBase64": "string-gigante-en-base64"
}



```

# Confirmar una transacción
```
# $occ y $externalUniqueNumber vienen dados en la respuesta de Transaction::create
$occ = 'valorocc';
$externalUniqueNumber = 'valorExternalUniqueNumber';

$commitResponse = Transaction::commit($occ, $externalUniqueNumber)

# Retorn un objeto TransactionCommitResponse con getters (getNombreAtributo) y setters (setNombreAtributo) para:

responseCode: Resultado de la creación de la Transacción
description: Descripción del responseCode
occ: Número orden de compra comercio
authorizationCode: Código de autorización. Usado si se desea anular la transacción
issuedAt: Momento de creación en UNIX time de la transacción
signature: Firma para verificación de datos
amount: Monto de la compra
transactionDesc: Tipo de venta
InstallmentsAmount: Valor de la cuota
InstallmentsNumber: Cantidad de cuotas
buyOrder: Orden de compra

# Adicionalmente, implementa JsonSerializable, por lo tanto:

json_encode($commitResponse);

# Retorna un JSON con la siguiente forma:

{
    "responseCode": "OK",
    "description": "OK",
    "occ": "1807419329781765",
    "authorizationCode": "906637",
    "issuedAt": 1530822491,
    "signature": "oM1mqjNfH/mv2TxR5Qf4VN0hr6eNCLsjfjJShdr9Vg0=",
    "amount": 2490,
    "transactionDesc": "Venta Normal: Sin cuotas",
    "installmentsAmount": 2490,
    "installmentsNumber": 1,
    "buyOrder": "20180705161636514"
}


```

# Anular una transacción
```
# Todos estos valores son obtenibles desde una respuesta a Transaction::commit

# Monto de la compra
$amount = $commitResponse->getAmount();

# Valor de occ
$occ = $commitResponse->getOcc();

# Valor externalUniqueNumber
$externalUniqueNumber = $commitResponse->getExternalUniqueNumber();

# Valor de authorizationCode
$authorizationCode = $commitResponse->getAuthorizationCode();

$refund = Refund::create($amount, $occ, $externalUniqueNumber,
                         $authorizationCode)

# Retorna un objeto RefundCreateResponse con los siguientes atributos:

occ: Orden de compra comercio
externalUniqueNumber: Número utilizado por el comercio para identificar la transacción
reverseCode: Código de la reversa
issuedAt: Momento de emision en tiempo UNIX
signature: Firma para validar los datos

# Adicionalmente, implementa JsonSerializble, por lo tanto:
json_encode($refund);

# Retorna un JSON de forma:
{
    "occ": "1807419329781765",
    "externalUniqueNumber": "1532103675510",
    "reverseCode": "623245",
    "issuedAt": 1530822491,
    "signature": "i1xFsNiky1VrEoXWUWXqGh9R4yg1/rfZhczEChhwHEU="
}


```

# Opciones
En caso de que lo requieras, puedes poner api key y shared secret alternativos en un request en particular:
```
use Transbank\Options;

$options = new Options('otro-api-key', 'otro-shared-secret');

# Al crear transacción
$transaction = Transaction::create($carro, $options);
json_encode($transaction);

# Al confirmar transacción
$commitTransaction = Transaction::commit($occ, $externalUniqueNumber, $options)
json_encode($commitTransaction);

# Al anular
$refund = Refund::create($amount, $occ, $externalUniqueNumber,
                         $authorizationCode, $options);
```
