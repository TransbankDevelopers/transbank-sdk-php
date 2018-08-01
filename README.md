# Transbank SDK

Requisitos:

- PHP 5.5+

En caso de instalar con Composer las siguientes dependencias deberían instalarse
automaticamente, pero si usas el SDK de manera directa requerirás también: 
- ext-curl
- ext-json
- ext-mbstring

Para usar el SDK en tu proyecto puedes usar Composer, por añadiendo el repositorio a tu composer.json: 

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/TransbankDevelopers/transbank-sdk-php"
        }
    ]
```

y luego requiriendo el SDK como dependencia:
```json
    "require": {
        "transbank-sdk": "dev-master#1.0.0-pre"
    }
```

O, si no deseas usar Composer, puedes descargar el código desde este repositorio y requerirlo directamente:
```php
require_once('/directorio/del/sdk/init.php');
```

Luego, para usar el SDK en tu código:

# Crear una transacción
```php
use Transbank\Onepay\OnepayBase;
use Transbank\Onepay\ShoppingCart;
use Transbank\Onepay\Item;
use Transbank\Onepay\Transaction;
use Transbank\Onepay\Refund;


OnepayBase::setApiKey('tu-api-key');
OnepayBase::setSharedSecret('tu-shared-secret');
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
    ott: Versión corta de "occ", usada en la app de Onepay (el usuario puede ingresar el OTT en vez de scanear el QR)
    signature: Firma para verificación de datos
    externalUniqueNumber: Valor usado por el comercio para identificar la transacción
    issuedAt: Momento de creación en UNIX time de la transacción
    qrCodeAsBase64: Código QR en base64. Sirve para que el usuario de Onepay pueda scanearlo con la app para   realizar el pago

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
```php
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
```php
# $amount y $occ son obtenibles a partir de la respuesta a Transaction::commit

# Monto de la compra
$amount = $commitResponse->getAmount();

# Valor de occ
$occ = $commitResponse->getOcc();

# $externalUniqueNumber se obtiene al crear una transacción (Transaction::create)
# y también se utiliza como parámetro al confirmar una transacción (Transaction::commit)

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
```php
use Transbank\Onepay\Options;

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

# Ambientes
El SDK incluye distintos ambientes (ej: TEST, LIVE, MOCK)

- LIVE: Producción.

- TEST: Servidor de pruebas de Transbank.

- MOCK: Servidor de pruebas que retorna respuestas predefinidas.


el cual se establece con:


```php
$type = "LIVE";
OnepayBase::setCurrentIntegrationType($type);
```

Los tipos de ambiente se pueden obtener con:

```php
OnepayBase::integrationTypes();
```
lo cual devuelve:
```php
["TEST" => "https://test.url.com", "LIVE" => "https://live.url.com", "MOCK" => "https://mock.url.com"]
```

También puedes obtener la URL de un ambiente directamente:
```php
OnepayBase::getIntegrationTypeUrl("LIVE");
# Retorna "https://live.url.com"
```

O la del ambiente actual
```php
OnepayBase::getCurrentIntegrationType();
# Retorna "LIVE" (o "TEST", o cual sea el ambiente actual)
OnepayBase::getCurrentIntegrationTypeUrl();
# Retorna "https://live.url.com", o la URL que corresponda al ambiente actual
```


