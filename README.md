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
- ext-soap

# Instalación

### Instalar con Composer

Para usar el SDK en tu proyecto puedes usar Composer (si no lo tienes instalado, puedes instalarlo [acá](https://getcomposer.org/)), añadiendo el SDK como dependencia a tu proyecto:
```json
    "require": {
        "transbank/transbank-sdk": "^1.7"
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


## Documentación 

Puedes encontrar toda la documentación de cómo usar este SDK en el sitio https://www.transbankdevelopers.cl.

La documentación relevante para usar este SDK es:

- Documentación general sobre los productos y sus diferencias:
  [Webpay](https://www.transbankdevelopers.cl/producto/webpay) y
  [Onepay](https://www.transbankdevelopers.cl/producto/onepay).
- Documentación sobre [ambientes, deberes del comercio, puesta en producción,
  etc](https://www.transbankdevelopers.cl/documentacion/como_empezar#ambientes).
- Primeros pasos con [Webpay](https://www.transbankdevelopers.cl/documentacion/webpay) y [Onepay](https://www.transbankdevelopers.cl/documentacion/onepay).
- Referencia detallada sobre [Webpay](https://www.transbankdevelopers.cl/referencia/webpay) y [Onepay](https://www.transbankdevelopers.cl/referencia/onepay).

## Información para contribuir y desarrollar este SDK

### Requerimientos
- Docker
- Make
- Plugin de editorconfig para tu editor favorito.

### Standares

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
3. Modificar el archivo `composer.json` para que la propiedad `"version"` apunte a la nueva versión `X.Y.Z`

Luego de obtener aprobación del pull request, debe mezclarse a master e inmediatamente generar un release en GitHub con el tag `X.Y.Z`. En la descripción del release debes poner lo mismo que agregaste al changelog.

Con eso Travis CI generará automáticamente una nueva versión de la librería y la publicará en Packagist.

### Vulnerabilidades de seguridad
Si descubres una falla de seguridad dentro de este proyecto, por favor, notifícanos por correo electrónico a transbankdevelopers@continuum.cl. Tomaremos el caso con la mayor celeridad. 
