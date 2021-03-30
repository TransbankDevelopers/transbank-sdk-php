# Ejemplos del SDK

Este directorio contiene algunos archivos de ejemplo para probar en **integración**, que podrás usar para tener una idea general sobre cómo integrar el SDK en tu aplicación.

Los ejemplos de transacciones que contiene son:

* Webpay Plus Normal
* Webpay Plus Mall Normal
* Webpay Oneclick (Registro, Cargo, Revertir y Eliminar subscripción).

## Uso

Primero, deberás iniciar este paquete usando Composer. Si no lo has hecho, puedes leer la sección de instalación en el [`README.md`](../README.md) que está en la raíz de este paquete.

Una vez instalado, y las dependencias de composer presentes, sólo debes ejecutar el servidor integrado de PHP esta línea de código (asumiendo que estás en este directorio).

```bash
php -S localhost:8080 -t .\ 
```

Esto creará un servidor local al cual podrás acceder vía [`http://localhost:8080/`](http://localhost:8080/), con vínculos a cada ejemplo.
