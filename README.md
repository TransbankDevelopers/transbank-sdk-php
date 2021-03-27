[![Build Status](https://semaphoreci.com/api/v1/continuum/transbank-sdk-php/branches/master/badge.svg)](https://semaphoreci.com/continuum/transbank-sdk-php)
[![Latest Stable Version](https://poser.pugx.org/transbank/transbank-sdk/v/stable)](https://packagist.org/packages/transbank/transbank-sdk)

# Transbank PHP SDK

SDK Oficial de Transbank

## Requisitos:

* PHP 8.0 o superior
* `ext-json` (Extensión para manejar JSON)

**Dependencias**

* [Cliente HTTP](#cliente-http)
* [Logger](#logger-opcional) (opcional)
* [Despachador de Eventos](#eventos-opcional) (opcional)

> Este SDK no incluye un cliente HTTP para que puedas usar el tuyo. Si no tienes uno, puedes [instarlo aparte](#cliente-http).

# Instalación

Para usar el SDK en tu proyecto **debes** usar [Composer](https://getcomposer.org/), añadiendo el SDK como dependencia a tu proyecto:

    composer require transbank/transbank-sdk:>=2.0

> Nota: Si usas un hosting compartido o un ambiente donde no tienes acceso para instalar Composer, tendrás que instalar el SDK en tu propio computador, y luego subir todos los archivos a tu aplicación por FTP, SSH, u otro. Quizás también tengas que instalar un [Cliente HTTP](#cliente-http) antes de subir todo.

## Documentación 

Puedes encontrar toda la documentación de cómo usar este SDK en el sitio de [Transbank Developers](https://www.transbankdevelopers.cl).

La documentación relevante para usar este SDK es:

- Primeros pasos con [Webpay](https://www.transbankdevelopers.cl/documentacion/webpay) y [Onepay](https://www.transbankdevelopers.cl/documentacion/onepay).
- Documentación sobre [ambientes, deberes del comercio, puesta en producción, etc](https://www.transbankdevelopers.cl/documentacion/como_empezar#ambientes).
  
También puedes encontrar:

- Documentación general sobre los productos y sus diferencias, como [Webpay](https://www.transbankdevelopers.cl/producto/webpay) y [Onepay](https://www.transbankdevelopers.cl/producto/onepay).
- Referencia detallada sobre [Webpay](https://www.transbankdevelopers.cl/referencia/webpay) y [Onepay](https://www.transbankdevelopers.cl/referencia/onepay).

## Comienzo rápido

El SDK de Transbank es muy fácil de usar. Simplemente crea una instancia con `make()`, que usa el cliente HTTP de Guzzle o Symfony si uno de ellos ya está instalado.

```php
use Transbank\Sdk\Transbank;

$transbank = Transbank::make();
```

Como es un objeto, te recomendamos guardarlo en algún lugar para no tener que crear una instancia nueva cada vez que necesites usar el SDK de Transbank.

También puedes usar el método `singletonBuilder()` para registrar una función que construya la instancia de `Transbank`, y luego usar `singleton()` para recuperarla -- ésta ejecuta la función sólo una vez si la instancia existe.

```php
use Transbank\Sdk\Transbank;

Transbank::singletonBuilder(function () : Transbank {
    return Transbank::make()->toProduction([
        'webpay' => ['key' => '...', 'secret' => '...']
    ]);
});

$tbk = Transbank::singleton();
```

### Ambientes y Credenciales

Por defecto, el SDK opera en modo **integración**, o sea, todas las transacciones son falsas.

Cuando quieras pasar a producción, necesitarás credenciales. Puedes hacerlo usando `toProduction()` usando un `array` con el nombre de los servicios y las credenciales de cada uno: `webpay`, `webpayMall`, `oneclickMall`, `fullTransaction` y `patpass`.

```php
$transbank->toProduction([
    'patpass' => ['555876543210','7a7b7d6cce5e...']
]);
```

> Para el caso de operaciones Mall, el código de comercio "hijo" es solamente requerido al hacer la transacción.

### Servicios

Para usar los servicios Webpay, Webpay Mall, Oneclick Mall, Transacción Completa y Patpass, simplemente llama el nombre del servicio desde la instancia de Transbank:

```php
use Transbank\Sdk\Transbank;

$transaction = Transbank::singleton()
        ->webpay()
        ->create('order#123', 9990, 'https://app.com/compra');
```

### Cliente HTTP

Este paquete es compatible con cualquier Cliente HTTP PSR-18, lo que te permitirá conectarte a Transbank con cualquiera que sea compatible.

> Si no tienes uno, te recomendamos usar [Guzzle](https://docs.guzzlephp.org/) o [Symfony](https://symfony.com/doc/current/http_client.html)
>
>     composer require guzzlehttp/guzzle:>=7.0
>
>     composer require symfony/http-client:>=5.2

La razón por la que el Cliente HTTP no es incluido con este SDK es para que puedas usar el tuyo. Si bien Guzzle y Symfony cumplen bien su trabajo, algunas plataformas de PHP incluyen uno especial, como [Amp](https://amphp.org/http-client/), [ReactPHP](https://reactphp.org/http/), o [Swoole](https://www.swoole.co.uk/docs/modules/swoole-coroutine-http-client).

### Logger (opcional)

Este paquete es compatible con cualquier logger PSR-3, lo que te permitirá revisar las operaciones de este SDK detalladamente.

> Si no tienes uno, te recomendamos usar [Monolog](https://github.com/Seldaek/monolog).
>
>     composer require monolog/monolog:>=2.0

El SDK de Transbank escribe como `debug` todas las transacciones enviadas y respuestas recibidas de Transbank.

> Si planeas guardar las transacciones, te recomendamos hacerlo manualmente en vez de usar el logger.

### Eventos (opcional)

Este paquete es compatible con cualquier Despachador de Eventos PSR-14, por lo que podrás _oír_ transacciones.

> Si no tienes uno, te recomendamos usar [Symfony Event Dispatcher](https://github.com/symfony/event-dispatcher)
>
>      composer require symfony/event-dispatcher:>=5.0

El SDK despacha eventos cada vez que una transacción se crea y se completa, independiente del éxito, junto al nombre del servicio que lo creó.

### Excepciones

Todas las excepciones de este SDK implementan `TransbankException`, por lo que podrás identificar fácilmente cualquier error desde tu aplicación y capturarla.

> Transacciones rechazadas no elevan excepciones.

Existen 3 tipos de excepciones en este SDK:

* `ClientException`: Cualquier error cometido por una transacción mal echa o mala configuración, como
  un monto negativo, o credenciales erróneas.
* `NetworkException`: Cualquier error producto de comunicación errónea con Transbank, como cuando se cae la Internet.
* `ServerException`: Cualquier error anormal desde Transbank que no sea tu culpa.
* `UnknownException`: Cualquier error desconocido.

Todas las excepciones contienen:

1. La transacción.
2. El mensaje enviado a Transbank, si la hay.
3. La respuesta de Transbank, si la hay.

Las excepciones son geniales para identificar qué pasó, dónde, y por qué. Por ejemplo, si la Internet _se cae_, recibirás un `CommunicationException`, así que podrás decirle a tus clientes que intenten nuevamente más tarde.

> Las excepciones **NO** son [_loggeadas_](#logger-opcional), eso es trabajo de tu aplicación.

## Información para contribuir y desarrollar este SDK

### Estándares

Para los commits respetamos las siguientes [normas de Chris Beams](https://chris.beams.io/posts/git-commit/):

* Usamos **inglés** para los mensajes de commit.
* Se pueden usar tokens como `WIP`, en el subject de un commit, separando el token con `:`, por ejemplo:
`WIP: This is a useful commit message`
* Para los nombres de ramas también usamos **inglés**.
* Se asume que una rama de `feature` no mezclada, es un feature no terminado.
* El nombre de las ramas va en **minúsculas**.
* Las palabras se separan con `-`.
* Las ramas comienzan con alguno de los _short lead_ tokens definidos, por ejemplo: `feat/tokens-configuration`

#### Tokens

Para los commits, `WIP` equivale a "Trabajo en progreso".

Para las nuevas ramas, aceptamos:

* `feat`: Nuevos features (características).
* `chore`: Tareas invisibles al usuario final.
* `bug`: Resolución de errores.

**Todas las mezclas a master se hacen mediante Pull Request.**

### Test

Para ejecutar los test localmente puedes usar Composer directamente, asumiendo que lo tienes instalado:

    composer install && composer run-script test

Si estás en linux, podrás ejecutar los tests en un contenedor de [Docker](https://docs.docker.com/get-docker/).

```bash
make && make test
```

### Publicando una nueva versión.

Para generar una nueva versión, se debe crear un PR (Pull Request) con un título "Prepare release {version}", donde `{version}` corresponde al [estándar semver](https://semver.org/lang/es/): 

* una nueva versión mayor (como `3.0`) si no hay compatibilidad con la versión anterior,
* una versión menor (como `2.12`) para mejoras que son retrocompatibles,
* o una versión parche (como `2.12.1`) si sólo hubo correción de errores.

En cada PR deben incluirse los siguientes cambios:

1. Modificar el archivo `CHANGELOG.md` para incluir una nueva entrada (al comienzo) para `X.Y.Z` que explique, en español, **los cambios para usuario del SDK**.
2. Modificar este `README.md` para que los ejemplos usen la nueva versión `X.Y.Z`

Luego de obtener aprobación del PR, se mezclará a la rama `master` y se generará, a corto plazo por el equipo, un release en GitHub con el tag `X.Y.Z`. En la descripción del _release_ debes poner lo mismo que agregaste al `CHANGELOG.md`.

Con eso Travis CI generará automáticamente una nueva versión de la librería y la publicará en Packagist.

### Vulnerabilidades de seguridad

Si descubres una falla de seguridad dentro de este proyecto, por favor, notifícanos por correo electrónico a transbankdevelopers@continuum.cl, en vez de publicarlo. Tomaremos el caso con la mayor celeridad. 
