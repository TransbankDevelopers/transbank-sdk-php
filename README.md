[![Build Status](https://semaphoreci.com/api/v1/continuum/transbank-sdk-php/branches/master/badge.svg)](https://semaphoreci.com/continuum/transbank-sdk-php)
[![Latest Stable Version](https://poser.pugx.org/transbank/transbank-sdk/v/stable)](https://packagist.org/packages/transbank/transbank-sdk)

# Transbank PHP SDK

![transbank-php-sdk](https://user-images.githubusercontent.com/1103494/113464583-14856a80-9404-11eb-947e-dd4dd4ff6510.png)

## Requisitos:

- PHP 7.0+

## Dependencias
Para utilizar este SDK, debes tener las siguientes extensiones de PHP instaladas: 
- ext-curl
- ext-json
- ext-mbstring

# Instalación

### Instalar con Composer

Para usar el SDK en tu proyecto **debes** usar Composer (si no lo tienes instalado, puedes instalarlo [acá](https://getcomposer.org/)), añadiendo el SDK como dependencia a tu proyecto:
```json
    "require": {
        ...
        "transbank/transbank-sdk": "~2.0"
    }
```

Luego debes correr 
```
composer update
```

También puedes instalarlo directamente desde la consola:
```bash
composer require transbank/transbank-sdk:~2.0
```

#### Despues de instalar... 
Si es primera vez que usas composer en tu proyecto, se creará una carpeta `vendor/` con el SDK y todo lo necesario.
Te recomendamos leer como funciona Composer, pero para comentar un punto importante, ahora asegúrate de que se incluye 
el archivo `vendor/autoload.php` en tu proyecto, idealmente al inicio de todo tu código. 

Si tu proyecto ya usa composer, la librería ya estará disponible para ser usaada sin que tengas que hacer nada 
adicional. 

#### Hosting compartido (no recomendado)
Si usas un hosting compartido o un ambiente donde no tienes acceso para instalar Composer, no te preocupes: Puedes 
instalar composer en tu propio computador, instalar el paquete como se indica anteriormente, y luego subir por FTP, SSH 
o como sea, todos los archivos del proyecto. No es necesario tener instalado composer en el servidor. Solo es necesario 
que los archivos generados tras la instalación de composer si estén. 
De todas formas, te recomendamos usar algún servidor VPS o dedicado, donde tendrás mas control de tu sistema.

## Ejemplos de código básicos
Dejamos **algunos** ejemplos básicos de código en la carpeta `examples` de este repositorio que te recomendamos revisar. 
Puedes ejecutar este ejemplo entrando a esta carpeta en tu terminal y ejecutando un servidor de PHP: 
```bash 
cd /path/to/this/sdk
cd examples
php -S 127.0.0.1:8000
```
Luego, solo abre [http://127.0.0.1:8000] en tu navegador web. 

## Proyecto de ejemplo completo
Adicionalmente, creamos un proyecto de ejemplo donde se implementan todos los productos y modalidades acá: https://github.com/TransbankDevelopers/transbank-sdk-php-webpay-rest-example/

## Documentación Transbank Developers
Puedes encontrar toda la documentación de cómo usar este SDK en el sitio https://www.transbankdevelopers.cl.

La documentación relevante para usar este SDK es:

- Primeros pasos con [Webpay](https://www.transbankdevelopers.cl/documentacion/webpay)
- Documentación sobre [ambientes, deberes del comercio, puesta en producción,
  etc](https://www.transbankdevelopers.cl/documentacion/como_empezar#ambientes).
  
También puedes encontrar: 
- Documentación general sobre los productos y sus diferencias:
  [Webpay](https://www.transbankdevelopers.cl/producto/webpay) y
  [Onepay](https://www.transbankdevelopers.cl/producto/onepay).
- Referencia detallada sobre [Webpay](https://www.transbankdevelopers.cl/referencia/webpay) y [Onepay](https://www.transbankdevelopers.cl/referencia/onepay).

## Información para contribuir y desarrollar este SDK

### Estándares

- Para los commits respetamos las siguientes normas: https://chris.beams.io/posts/git-commit/
- Usamos ingles, para los mensajes de commit.
- Se pueden usar tokens como WIP, en el subject de un commit, separando el token con `:`, por ejemplo:
`WIP: This is a useful commit message`
- Para los nombres de ramas también usamos ingles.
- Se asume, que una rama de feature no mezclada, es un feature no terminado.
- El nombre de las ramas va en minúsculas.
- Las palabras se separan con `-`.
- Las ramas comienzan con alguno de los short lead tokens definidos, por ejemplo: `feat/tokens-configuration`

#### Short lead tokens
##### Commits
- WIP = Trabajo en progreso.
##### Ramas
- feat = Nuevos features
- chore = Tareas, que no son visibles al usuario.
- bug = Resolución de bugs.

### Todas las mezclas a master se hacen mediante Pull Request.

### Test
Para ejecutar los test localmente debes ejecutar los siguientes comandos en una terminal.

```bash
make
```

Y luego ejecutar los test

```bash
make test
```

### Deploy de una nueva versión.
Para generar una nueva versión, se debe crear un PR (con un título "Prepare release X.Y.Z" con los valores que correspondan para `X`, `Y` y `Z`). Se debe seguir el estándar semver para determinar si se incrementa el valor de `X` (si hay cambios no retrocompatibles), `Y` (para mejoras retrocompatibles) o `Z` (si sólo hubo correcciones a bugs).

En ese PR deben incluirse los siguientes cambios:

1. Modificar el archivo `CHANGELOG.md` para incluir una nueva entrada (al comienzo) para `X.Y.Z` que explique en español los cambios **de cara al usuario del SDK**.
2. Modificar este `README.md` para que los ejemplos usen la nueva versión `X.Y.Z`

Luego de obtener aprobación del pull request, debe mezclarse a master e inmediatamente generar un release en GitHub con el tag `X.Y.Z`. En la descripción del release debes poner lo mismo que agregaste al changelog.

Con eso Travis CI generará automáticamente una nueva versión de la librería y la publicará en Packagist.

### Vulnerabilidades de seguridad
Si descubres una falla de seguridad dentro de este proyecto, por favor, notifícanos por correo electrónico a transbankdevelopers@continuum.cl. Tomaremos el caso con la mayor celeridad. 
