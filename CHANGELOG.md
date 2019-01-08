# Changelog
Todos los cambios notables a este proyecto serán docuemntados en este archivo.

El formato está basado en [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
y este proyecto adhiere a [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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
