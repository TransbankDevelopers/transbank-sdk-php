# Changelog
Todos los cambios notables a este proyecto serán docuemntados en este archivo.

El formato está basado en [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
y este proyecto adhiere a [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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
