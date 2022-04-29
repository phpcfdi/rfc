# CHANGELOG

## SemVer 2.0

Utilizamos [Versionado Semántico 2.0.0](SEMVER.md).

## Cambios no liberados

Los cambios no liberados no requieren de una nueva versión y son incluidos en la rama principal.

## Versión 1.1.2

Se actualiza la clase `CheckSum` y se mejoran las pruebas unitarias sobre la misma.
Gracias a `@fitorec` por sus sugerencias en el [PR #14](https://github.com/phpcfdi/rfc/pull/14).

Se actualizan las versiones de las herramientas de desarrollo.

Al ejecutar el trabajo de integración continua en el trabajo `phpcs` se usan los directorios según
el archivo de configuración. 

## Versión 1.1.1

Se actualiza la expresión regular para lectura de RFC con las recomendaciones de simplificación:
se sustituye `[0-9]` por `\d` y se elimina el calificador `{1}` innecesario. 

Se incluyen los cambios previos no liberados de mantenimiento.

### 2022-02-23 Mantenimiento

- Se actualiza el año en el archivo de licencia. Feliz 2022.
- Se actualizan las herramientas de desarrollo.
- Se actualiza el archivo de configuración de Psalm, el atributo `totallyTyped` está deprecado.
- Se agrega PHP 8.1 a la matriz de pruebas de PHP.
- Se crea la clase abstracta `PhpCfdi\Rfc\Tests\TestCase` para no depender directamente de `PHPUnit\Framework\TestCase`.

### 2022-01-12 Mantenimiento

- Se separan los flujos de integración continua en `build` y `coverage`.
- Se usa SonarCloud para llevar la calidad del proyecto en lugar de Scrutinizer. Gracias Scrutinizer.
- Se modifican los *badges* en el archivo `README`.
- Se corrige el nombre del grupo de mantenimiento de PhpCfdi.

### 2021-11-10 Revisión de archivos de desarrollo

- Se corrige la documentación para el uso de la librería con `faker` en Laravel.
- Se corrigen los archivos excluidos del paquete de producción.
- Se actualiza PHPStan a 1.1.2.
- Se cambia de `development/install-development-tools` a `phive` para administrar las dependencias de desarrollo.
- Se actualizan los estilos a `PSR-12` y las configuraciones de `phpcs` y `php-cs-fixer`.
- Se elimina la actualización de `composer` de Scrutinizer.
- Se elimina el archivo superfluo `development/EntornoDesarrollo.md`.
- Se agrega el proyecto a SonarCloud.

## Versión 1.1.0

Dependencias:

- Se cambia la dependencia `fzaninotto/Faker` a `FakerPHP/Faker`.

Documentación:

- Se modifica la documentación del proyecto en español, además de revisar textos actuales.
- Se pone la licencia correcta de *Carlos C Soto* a *PhpCfdi* con el año 2021.
  
Desarrollo:

- Se remueve la concatenación innecesaria en la expresión regular del RFC, se explica en comentarios.
- Se agrega la compatibilidad con PHP 8.0 a la matriz de Travis-CI.
- Se modifican los scripts de composer para que se ejecuten con el mismo programa de php con el que fueron invocados.

## Version 1.0.0

- Versión inicial.
