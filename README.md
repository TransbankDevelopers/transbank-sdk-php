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

O, si no deseas usar Composer, puedes descargar el código del SDK y requerirlo directamente:
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
$objeto1 = ("amount" => 100, "quantity": 2, "description" => 'descripción del item');
$objeto2 = ("amount" => 200, "quantity": 3, "description" => 'otro objeto distinto');

$losObjetos = ["items" => [$objeto1, $objeto2]];

$carro = ShoppingCart::fromJSON($losObjetos)

# description, quantity, amount;
$tercerObjeto = new Item('descripcion del objeto 3', 5, 100); 
$carro->add($tercerObjeto);

$transaction = Transaction::create($carro);

json_encode($transaction);


```

# Confirmar una transacción
```
# $occ y $externalUniqueNumber vienen dados en la respuesta de Transaction::create
$occ = 'valorocc';
$externalUniqueNumber = 'valorExternalUniqueNumber';

$commitResponse = Transaction::commit($occ, $externalUniqueNumber)

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

