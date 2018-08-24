[![Build Status](https://semaphoreci.com/api/v1/continuum/transbank-sdk-php/branches/master/badge.svg)](https://semaphoreci.com/continuum/transbank-sdk-php)
[![Latest Stable Version](https://poser.pugx.org/transbank/transbank-sdk/v/stable)](https://packagist.org/packages/transbank/transbank-sdk)

# Transbank PHP SDK

SDK Oficial de Transbank

## Requisitos:

- PHP 5.5+


## Dependencias
En caso de instalar con Composer las siguientes dependencias deberían instalarse
automaticamente, pero si usas el SDK de manera directa requerirás también: 
- ext-curl
- ext-json
- ext-mbstring

# Instalación

### Instalar con Composer

Para usar el SDK en tu proyecto puedes usar Composer, añadiendo el SDK como dependencia a tu proyecto:
```json
    "require": {
        "transbank/transbank-sdk": "1.1.0"
    }
```

También puedes instalarlo desde la consola:
```bash
composer require transbank/transbank-sdk
```

O, si no deseas usar Composer, puedes descargar el código desde este repositorio y requerirlo directamente:
```php
require_once('/directorio/del/sdk/init.php');
```

# Primeros pasos

### Onepay

#### Configuración de API_KEY y SHARED_SECRET

Existen varias formas de configurar esta información, la cual identifica a cada comercio:

##### 1. Por variable de entorno
```bash
export ONEPAY_SHARED_SECRET = "valor de tu shared secret"
export ONEPAY_API_KEY = "valor de tu api key"
export ONEPAY_CALLBACK_URL = "valor de tu callback url"
export ONEPAY_APP_SCHEME = "valor de tu app scheme"
```

##### 2. Configurando tu API_KEY y SHARED_SECRET al inicializar tu proyecto
Para esto es necesario importar `Transbank\Onepay\OnepayBase;`
```php
use Transbank\Onepay\OnepayBase;

OnepayBase::setSharedSecret('valor de tu shared secret');
OnepayBase::setApiKey('valor de tu api key');
OnepayBase::setCallbackUrl('valor de tu callback url');
OnepayBase::setAppScheme('valor de tu app scheme');
```

##### 3. Pasando el API_KEY y SHARED_SECRET a cada petición
Para esto es necesario importar Transbank\Onepay\Options;
```php
use Transbank\Onepay\Options;

$options = new Options('otro-api-key', 'otro-shared-secret');

# Al crear transacción
$transaction = Transaction::create($carro, ChannelEnum::WEB(), $options);

# Al confirmar transacción
$commitTransaction = Transaction::commit($occ, $externalUniqueNumber, $options)

# Al anular
$refund = Refund::create($amount, $occ, $externalUniqueNumber,
                         $authorizationCode, $options);
```

##### Ambientes TEST y LIVE
Por defecto el tipo de integración del SDK es siempre `TEST`.
Se puede obtener la información de los distintos ambientes disponibles utilizando
`Transbank\Onepay\OnepayBase`
```php
use Transbank\Onepay\OnepayBase;
OnepayBase::integrationTypes();
```
lo cual devuelve:
```php
["TEST" => "https://test.url.com", "LIVE" => "https://live.url.com"]
```

Puedes establecer el SDK para usar los servicios del ambiente `LIVE` (Producción), de la siguiente forma:

```php
$type = "LIVE";
OnepayBase::setCurrentIntegrationType($type);
```

##### Crear una nueva transacción
Para iniciar un proceso de pago mediante la aplicación móvil de Onepay, primero es necesario crear la transacción en Transbank. Para esto se debe crear en primera instancia un objeto `Transbank\Onepay\ShoppingCart`, el cual se debe llenar con  `Transbank\Onepay\Item`
```php
use Transbank\Onepay\ShoppingCart;
use Transbank\Onepay\Item;
use Transbank\Onepay\Transaction;

$carro = new ShoppingCart();

# description, quantity, amount;
$objeto = new Item('Pelota de futbol', 1, 20000); 
$carro->add(objeto);
```
Otra manera de crear un carro es con un arreglo asociativo (o JSON que con `json_decode` sea un arreglo asociativo)
```php
$objeto1 = ["amount" => 15000, "quantity" => 1, "description" => 'Zapatos deportivos'];
$objeto2 = ["amount" => 5000, "quantity" => 3, "description" => 'Calcetines'];

$losObjetos = ["items" => [$objeto1, $objeto2]];

# Crea un nuevo ShoppingCart con los 2 objetos includos en $losObjetos
$segundoCarro = ShoppingCart::fromJSON($losObjetos);
```

Teniendo un carro, se puede crear una `Transaction`
```php
$transaction = Transaction::create($carro, ChannelEnum::WEB());

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

En caso de que falle el `create` de una `Transaction` se devuelve un objeto de tipo `TransactionCreateException`, donde 
la propiedad `message`contiene la razón del fallo.

El segundo parámetro en el ejemplo corresponde al `$channel` y puede ser puede ser `ChannelEnum::WEB()`, 
`ChannelEnum::MOBILE()` o `ChannelEnum::APP()` dependiendo si quien está realizando el pago está usando un browser en 
versión Desktop, Móvil o está utilizando alguna aplicación móvil nativa.

En caso que `$channel` sea `ChannelEnum::MOBILE()` es obligatorio que esté previamente configurado el `$callbackUrl` o de 
lo contrario la aplicación móvil no podrá re-direccionar a este cuando el pago se complete con éxito y como consecuencia 
no podrás confirmar la transacción.

```php
OnepayBase::setCallbackUrl('http://www.somecallback.com/example');
```

En caso que `$channel` sea `ChannelEnum::APP()` es obligatorio que esté previamente configurado el `$appScheme`:

```php
OnepayBase::setAppScheme('mi-app://mi-app/onepay-result');
```

Posteriormente, se debe presentar al usuario el código QR y el número OTT para que pueda proceder al pago mediante la aplicación móvil.
##### Confirmar una transacción
Una vez que el usuario realizó el pago mediante la aplicación, dispones de 30 segundos para realizar la confirmación de la transacción, de lo contrario, se realizará automáticamente la reversa de la transacción.
```php
# $occ y $externalUniqueNumber vienen dados en la respuesta de Transaction::create
$occ = 'valorocc';
$externalUniqueNumber = 'valorExternalUniqueNumber';

$commitResponse = Transaction::commit($occ, $externalUniqueNumber)

# Retorna un objeto TransactionCommitResponse con getters (getNombreAtributo) y setters (setNombreAtributo) para:

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

En caso de que falle el `commit` de una `Transaction` se devuelve un objeto de tipo `TransactionCommitException`, donde la propiedad `message`contiene la razón del fallo.


##### Anular una transacción
Cuando una transacción fue confirmada correctamente, se dispone de un plazo de 30 días para realizar la anulación de esta.
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
En caso de que falle el `create` de un `Refund` se devuelve un objeto de tipo `RefundCreateException` donde la propiedad `message` contiene la razón del fallo.
