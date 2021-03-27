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

> Nota: Si usas un hosting compartido o un ambiente donde no tienes acceso para instalar Composer, tendrás que instalar el SDK en tu propio computador, y luego subir todos los archivos a tu aplicación por FTP, SSH, u otro.

## Documentación 

Puedes encontrar toda la documentación de cómo usar este SDK en el sitio de [Transbank Developers](https://www.transbankdevelopers.cl).

La documentación relevante para usar este SDK es:

- Primeros pasos con [Webpay](https://www.transbankdevelopers.cl/documentacion/webpay) y [Onepay](https://www.transbankdevelopers.cl/documentacion/onepay).
- Documentación sobre [ambientes, deberes del comercio, puesta en producción, etc](https://www.transbankdevelopers.cl/documentacion/como_empezar#ambientes).
  
También puedes encontrar: 
- Documentación general sobre los productos y sus diferencias, como [Webpay](https://www.transbankdevelopers.cl/producto/webpay) y [Onepay](https://www.transbankdevelopers.cl/producto/onepay).
- Referencia detallada sobre [Webpay](https://www.transbankdevelopers.cl/referencia/webpay) y [Onepay](https://www.transbankdevelopers.cl/referencia/onepay).

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
