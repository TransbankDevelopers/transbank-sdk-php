# Changelog
Todos los cambios notables a este proyecto serán docuemntados en este archivo.

El formato está basado en [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
y este proyecto adhiere a [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

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
