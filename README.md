![Build Status](https://github.com/TransbankDevelopers/transbank-sdk-php/actions/workflows/build.yml/badge.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/transbank/transbank-sdk/v/stable)](https://packagist.org/packages/transbank/transbank-sdk)

# Transbank PHP SDK

![transbank-php-sdk](https://user-images.githubusercontent.com/1103494/113464583-14856a80-9404-11eb-947e-dd4dd4ff6510.png)

## Requisitos:

- PHP 8.4+

## Dependencias

Para utilizar este SDK, debes tener las siguientes extensiones de PHP instaladas:

- ext-curl
- ext-json
- ext-mbstring

## Instalación

### Instalar con Composer

Para usar el SDK en tu proyecto **debes** usar Composer (si no lo tienes instalado, puedes instalarlo [acá](https://getcomposer.org/)), añadiendo el SDK como dependencia a tu proyecto:

```json
    "require": {
        ...
        "transbank/transbank-sdk": "~5.0"
    }
```

Luego debes correr

```bash
composer update
```

También puedes instalarlo directamente desde la consola:

```bash
composer require transbank/transbank-sdk:~5.0
```

### Tests y desarrollo

El entorno recomendado para desarrollar y ejecutar los tests es el devcontainer del
proyecto. En Visual Studio Code, abre la paleta de comandos y selecciona
`Dev Containers: Reopen in Container`. Al crear el contenedor se ejecuta
`composer install` automáticamente mediante `postCreateCommand`.

Con el contenedor abierto, ejecuta los tests desde la raíz del proyecto:

```bash
composer test
```

Para ejecutar los tests con reporte de coverage:

```bash
composer run test:coverage
```

También puedes instalar las dependencias manualmente cuando sea necesario:

```bash
composer install --no-interaction --prefer-dist
```

## Proyecto de ejemplo completo

Adicionalmente, creamos un proyecto de ejemplo donde se implementan todos los productos y modalidades acá: https://github.com/TransbankDevelopers/transbank-sdk-php-example

## Documentación Transbank Developers

Puedes encontrar toda la documentación de cómo usar este SDK en el sitio https://www.transbankdevelopers.cl.

La documentación relevante para usar este SDK es:

- Documentación general sobre el producto [Webpay](https://www.transbankdevelopers.cl/producto/webpay)
- Primeros pasos con [Webpay](https://www.transbankdevelopers.cl/documentacion/webpay)
- Documentación sobre [ambientes, deberes del comercio, puesta en producción,
  etc](https://www.transbankdevelopers.cl/documentacion/como_empezar#ambientes).
- Referencia detallada sobre [Webpay](https://www.transbankdevelopers.cl/referencia/webpay?l=php#)

## Información para contribuir a este proyecto

### Forma de trabajo

- Para los mensajes de commits, nos basamos en las Git Commit Guidelines de Angular.
- Usamos inglés para los nombres de ramas y mensajes de commit.
- Los mensajes de commit no deben llevar punto final.
- Los mensajes de commit deben usar un lenguaje imperativo y estar en tiempo presente, por ejemplo, usar "change" en lugar de "changed" o "changes".
- Los nombres de las ramas deben estar en minúsculas y las palabras deben separarse con guiones (-).
- Todas las fusiones a la rama principal se deben realizar mediante solicitudes de Pull Request(PR). ⬇️
- Se debe emplear tokens como "WIP" en el encabezado de un commit, separados por dos puntos (:), por ejemplo, "WIP: this is a useful commit message".
- Una rama con nuevas funcionalidades que no tenga un PR, se considera que está en desarrollo.
- Los nombres de las ramas deben comenzar con uno de los tokens definidos. Por ejemplo: "feat/tokens-configurations".

### Short lead tokens permitidos

`WIP` = En progreso.

`feat` = Nuevos features.

`fix` = Corrección de un bug.

`docs` = Cambios solo de documentación.

`style` = Cambios que no afectan el significado del código. (espaciado, formateo de código, comillas faltantes, etc)

`refactor` = Un cambio en el código que no arregla un bug ni agrega una funcionalidad.

`perf` = Cambio que mejora el rendimiento.

`test` = Agregar test faltantes o los corrige.

`chore` = Cambios en el build o herramientas auxiliares y librerías.

`revert` = Revierte un commit.

`release` = Para liberar una nueva versión.

### Creación de un Pull Request

- El PR debe estar enfocado en un cambio en concreto, por ejemplo, agregar una nueva funcionalidad o solucionar un error, pero un solo PR no puede agregar una nueva funcionalidad y arreglar un error.
- El título del los PR y mensajes de commit no debe comenzar con una letra mayúscula.
- No se debe usar punto final en los títulos.
- El título del PR debe comenzar con el short lead token definido para la rama, seguido de ":"" y una breve descripción del cambio.
- La descripción del PR debe detallar los cambios que se están incorporando.
- La descripción del PR debe incluir evidencias de que los test se ejecutan de forma correcta o incluir evidencias de que los cambios funcionan y no afectan la funcionalidad previa del proyecto.
- Se pueden agregar capturas, gif o videos para complementar la descripción o demostrar el funcionamiento del PR.

#### Flujo de trabajo

1. Crea tu rama desde develop.
2. Haz un push de los commits y publica la nueva rama.
3. Abre un Pull Request apuntando tus cambios a develop.
4. Espera a la revisión de los demás integrantes del equipo.
5. Para poder mezclar los cambios se debe contar con 2 aprobaciones de los revisores y no tener alertas por parte de las herramientas de inspección.

### Esquema de flujo con git

El trabajo debe seguir un flujo ordenado de ramas: feature branch desde `develop`, PR hacia `develop`, y release cuando corresponda. La rama release debe nacer desde `develop` y apuntar a `main`.

!gitflow

## Generar una nueva versión

Para generar una nueva versión, se debe crear un PR (con un título "release: prepare release X.Y.Z" con los valores que correspondan para `X`, `Y` y `Z`). Se debe seguir el estándar SemVer para determinar si se incrementa el valor de `X` (si hay cambios no retrocompatibles), `Y` (para mejoras retrocompatibles) o `Z` (si sólo hubo correcciones a bugs).

En ese PR deben incluirse los siguientes cambios:

1. Modificar el archivo `CHANGELOG.md` para incluir una nueva entrada (al comienzo) para `X.Y.Z` que explique en español los cambios **de cara al usuario del SDK**.
2. Modificar este `README.md` para que los ejemplos usen la nueva versión `X.Y.Z`

Luego de obtener aprobación del pull request, debe mezclarse a master e inmediatamente generar un release en GitHub con el tag `X.Y.Z`. En la descripción del release debes poner lo mismo que agregaste al changelog.

Con eso la CI generará automáticamente una nueva versión de la librería y la publicará en Packagist.

### Vulnerabilidades de seguridad

Si descubres una falla de seguridad dentro de este proyecto, por favor, notifícanos por correo electrónico a transbankdevelopers@continuum.cl. Tomaremos el caso con la mayor celeridad.
