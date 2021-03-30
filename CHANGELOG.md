# Changelog
Todos los cambios notables a este proyecto serán documentados en este archivo.

El formato está basado en [Keep a Changelog](http://keepachangelog.com/en/1.0.0/) y este proyecto adhiere a [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2021-03-26

- Requiere PHP 7.3 o mayor.
- Soporte inicial para Webpay Plus, Webpay Plus Mall, Webpay Oneclick Mall.
- Todos los productos de Transbank se manejan en una única instancia (el objeto `Transbank`).
- Única clase dinámica para la recepción de respuesta desde Transbank.
- Se agrega capacidad de permitir opciones en todas las transacciones (para uso futuro).
- Removida dependencia-dura de Cliente HTTP.
- Sólo se puede pasar a producción introduciendo credenciales de un servicio. 
- Se agrega soporte para cualquier Cliente HTTP (PSR-18) estándar.
- Se agrega soporte para logger (PSR-3) estándar, opcional.
- Se agrega soporte para despachador de eventos (PSR-14) estándar, opcional.
- Se agrega soporte por defecto de ambiente de integración.
- Se agrega excepciones para distintos tipos de errores (cliente, servidor, conexión, desconocido).
- Se agrega interfaz única para todas las excepciones de Transbank.
- Se agrega excepción para respuestas de Transbank con códigos HTTP 4xx y 5xx.
- Se agrega [Roave Security Advisories](https://github.com/Roave/SecurityAdvisories) para prevenir dependencias con problemas de seguridad.

## [1.10.1] - 2020-12-21
### Fixed
- Se soluciona error de syntax en algunos archivos
- Se añade `TransaccionCompleta::configureMallForTesting()` 

## [1.10.0] - 2020-12-10
### Added
- Se agrega soporte para Oneclick Mall Captura Diferida
- Se agrega helper para pasar a produccion Oneclick `Oneclick::configureForProduction($commerceCode, $apiKeySecret)`

### Fixed
- El response code de la respuesta de una reversa en Oneclick ya no es ignorado por el SDK.

## [1.9.0] - 2020-12-09
### Added
- Se agrega método helper `WebpayPlus::configureForProduction($commerceCode, $apiKeySecret)` [PR #153](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/153)
- Se añade travis para automatizar release en github - Se agrega método helper `WebpayPlus::configureForProduction($commerceCode, $apiKeySecret)` [PR #147](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/147)

### Fixed
- Se mejora el formato del código (PSR-2) [PR #156](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/156)
- Se mejora orden de archivos y carpetas para ser compatible con PSR-4 y evitar problema al instalar SDK usando composer2 [PR #157](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/157)
- Se resuelve error con parámetro `installments_number` que venía vacío al hacer un `commit` en Webpay Plus - Se agrega método helper `WebpayPlus::configureForProduction($commerceCode, $apiKeySecret)` [PR #150](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/150)

## [1.8.2] - 2020-11-20
### Fixed
- Ahora el SDK soporta tres versiones de Guzzle, para evitar conflictos de versiones: Guzzle 5.X, 6.X y 7.X. [PR #144](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/144)      

## [1.8.1] - 2020-10-26
### Fixed
- Se resuelve error de autoloading en clase TransaccionCompleta [PR #142](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/142)   

## [1.8.0] - 2020-10-19
### Added
- Se publican clases y métodos para integrar nueva API REST.  

## [1.7.3] - 2020-10-14
### Fixed
- Soluciona error que producía que la clase Oneclick (REST) no se encontrara [PR #132](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/132)
- Se actualizan dependencias [PR #133](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/133)

## [1.7.2] - 2020-08-06
### Fixed
- Arreglo error que se ocasionaba al tratar de leer el serial number de un certificado usando PHP 5.6 [PR #130](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/130)

## [1.7.1] - 2020-07-09
### Fixed
- Se arregló llamada a método de clase que había sido previamente eliminada

## [1.7.0] - 2020-06-22
### Fixed
- Se arregló error de opciones por defecto en Oneclick Mall REST [PR #123](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/123)
- Se arregló forma en que se retornan los errores en Oneclick Mall REST [PR #124](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/124)
- Importante: se arregla error "For input string 0xAB" al user certificados generados con OpenSSL 1.1 [PR #125](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/125)

### Added
- Se agregaron validaciones numéricas y de decimales a Webpay Plus `init Transaction` [PR #122](https://github.com/TransbankDevelopers/transbank-sdk-php/pull/122)

## [1.6.1] - 2020-02-20
### Fixed
- Se agrega soporte a las versiones de guzzle superiores a >= 6 y < 7 [https://github.com/TransbankDevelopers/transbank-sdk-php/pull/113].
- Se sube el soporte de la versión de php a 7.4 [https://github.com/TransbankDevelopers/transbank-sdk-php/pull/117].
- Se crea el archivo security.md [https://github.com/TransbankDevelopers/transbank-sdk-php/pull/115].
- La extensión SOAP de PHP es requerida [https://github.com/TransbankDevelopers/transbank-sdk-php/pull/99].

## [1.6.0] - 2019-12-26
### Added
- Se agrega soporte para Oneclick Mall y Transacción Completa en sus versiones REST.

## [1.5.3] - 2019-10-17
### Fixed
- Se corrige utilización de puerto 80 en las peticiones SOAP.

## [1.5.2] - 2019-05-22
### Fixed
- Se corrige asignación de certificado de Webpay cuando no es asignado en la configuración, se asigna automáticamente en base al entorno.

## [1.5.1] - 2019-01-08
### Changed
- Se elimina la condición de `VCI == "TSY" || VCI == ""` para evaluar la respuesta de `getTransactionResult` debido a que
esto podría traer problemas con transacciones usando tarjetas internacionales.

## [1.5.0] - 2018-12-20
### Added
- Se agrega soporte para poder configurar `$commerceLogoUrl` y `$qrWidthHeight`. El primero entrega soporte para que el 
app de onepay pueda mostrar el logo de comercio, mientras que el segundo entrega la posibilidad de pedir que la imagen 
QR venga en un tamaño especifico (útil para la modalidad de QR directo.)

## [1.4.4] - 2018-12-12
### Fixed
- Se corrige un problema con los nombres de archivos de varias clases que provocaba errores principalmente en Linux. Esto corrige además el problema reportado con Larabel.

## [1.4.3] - 2018-11-06
### Fixed
- Se actualiza certificado de producción requerido para que webpay funcione correctamente.

## [1.4.2] - 2018-10-31
### Added
- Soporte PHP 7+ para Webpay.
### Fixed
- Se estaba usando buy order como monto y monto como buy order en webpay mall normal. 

## [1.4.1] - 2018-10-27
### Fixed
- Se corrige carga de extensiones en Onepay, no requiere tener extensiones que usa
Webpay SOAP.

## [1.4.0] - 2018-10-25
### Added
- Incorpora soporte Webpay SOAP en PHP 5.x (No soporta PHP 7 aún)
### Changed
- El carro de compras soporta agregar items con valores negativos. Útil en caso
de necesitar incluir descuentos.

## [1.3.2] - 2018-10-03
### Fixed
- Se corrige el orden de clases cargadas en el archivo init.php para ser usado sin problemas
cuando no se usa composer.

## [1.3.1] - 2018-09-11
### Fixed
- `OnepayRequestBuilder::buildOptions` no setea el appKey correcto en caso que se entregue como base
un `options` nulo. Se corrige este error seteando en el constructor de `options` el appKey
correcto en vez de dejarlo null.

## [1.3.0] - 2018-09-10
### Added
- Configuración de las credenciales de integración por defecto. Ya no será necesario que
configures API_KEY o SHARED_SECRET si quieres trabajar en ambiente TEST.

## [1.2.2] - 2018-08-30
### Changed
- Apunta entornos a los servidores oficiales para `TEST` y `LIVE`. De ahora en
adelante, el SDK puede ser usado para validaciones oficiales y será
interoperable con las herramientas provistas por Transbank para ayudar esa
integración y validación (como el dashboard para simular transacciones).
