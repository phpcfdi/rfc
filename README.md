# `phpcfdi/rfc`

[![Source Code][badge-source]][source]
[![Packagist PHP Version Support][badge-php-version]][php-version]
[![Discord][badge-discord]][discord]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Reliability][badge-reliability]][reliability]
[![Maintainability][badge-maintainability]][maintainability]
[![Code Coverage][badge-coverage]][coverage]
[![Violations][badge-violations]][violations]
[![Total Downloads][badge-downloads]][downloads]

> Librería de PHP para trabajar con RFC.

:us: The documentation of this project is in spanish as this is the natural language for the intended audience.

## Acerca de

En México, toda persona física o moral para realizar cualquier actividad económica requiere de un registro
ante la Secretaría de Hacienda y Crédito Público (SHCP) llamado Registro Federal de Contribuyentes (RFC).

Esta librería permite trabajar con esta clave desde el aplicativo de PHP.

## Instalación

Usa [composer](https://getcomposer.org/)

```shell
composer require phpcfdi/rfc
```

## Uso básico

```php
use PhpCfdi\Rfc\Rfc;
// suponiendo que llega un formulario con rfc => COSC8001137NA
$rfc = Rfc::parse($_POST['rfc']);
echo $rfc->getRfc(); // COSC8001137NA
echo $rfc; // COSC8001137NA
echo json_encode(['data' => $rfc]); // {"data": "COSC8001137NA"}
var_dump($rfc->isFisica()); // bool(true)
var_dump($rfc->isMoral()); // bool(false)
var_dump($rfc->isGeneric()); // bool(false)
var_dump($rfc->isForeign()); // bool(false)
```

## Creación de objetos

El objeto `Rfc` se puede crear a partir de cuatro formas:

- `Rfc::parse(string): Rfc`: Se validan los datos de entrada y surge una excepción si son inválidos.
- `Rfc::parseOrNull(string): ?Rfc`: Se validan los datos de entrada y retorna nulo si son inválidos.
- `Rfc::unparsed(string): Rfc`: No se validan los datos de entrada, se creará el objeto con la cadena de caracteres como Rfc.
- `Rfc::fromSerial(int): Rfc`: Se convierte el número de serie del RFC a su representación de cadena de caracteres.

No se puede crear un objeto a partir del constructor `new Rfc`. Use `Rfc::unparsed` en su lugar.

Se recomienda que, siempre que se crea el objeto y los datos de origen no son de confianza, se utilice `Rfc::parse`.

El único dato importante dentro del RFC es la cadena de caracteres misma. Por ello se ha implementado que la conversión
a cadena de caracteres y la exportación a JSON devuelvan específicamente este dato.

## Números de serie

La representación del *número de serie* corresponde a un número creado con esta misma librería,
este número es un entero de 64 bits que se puede almacenar como un entero largo en una base de datos.

Para obtener el número de serie de un RFC puede usar el método `Rfc::calculateSerial()`.

Para crear un Rfc a partir de un entero puede usar `Rfc::fromSerial()`.

La clase responsable de los cálculos involucrados en esta conversión está optimizada con arreglos constantes
de conversión por lo que su ejecución es lo más veloz que puede ser.

## RFC genérico y foráneo

Es frecuente utilizar RFC que son *virtuales*, por ejemplo, para operaciones sin identificar como una
venta de mostrador u operaciones con extranjeros, en estos casos están las constantes
`Rfc::RFC_GENERIC = 'XAXX010101000'` y `Rfc::RFC_FOREIGN = 'XEXX010101000'` respectivamente.

Puede usar los métodos `Rfc::newGeneric()` y `Rfc::newForeign()` para crear instancias con estos datos.

Si se desea saber que el RFC es genérico se puede usar el método `Rfc::isGeneric()` y para RFC extranjero `Rfc::isForeign()`.

## Generador de RFC

Es común usar generadores (ficticios) de datos, esta librería provee la clase `RfcFaker` que se puede utilizar
por sí sola o en conjunto con [`FakerPHP/Faker`](https://github.com/FakerPHP/Faker).

Provee métodos para crear una cadena de caracteres que es una clave RFC:

- `RfcFaker::mexicanRfcFisica()` para persona física (13 posiciones).
- `RfcFaker::mexicanRfcMoral()` para persona moral (12 posiciones).
- `RfcFaker::mexicanRfc()` indistintamente una persona moral o física.

## Dígito verificador

Se puede obtener el dígito verificador calculado con el método `Rfc::calculateCheckSum()`,
así como conocer si el dígito verificador coincide con el método `Rfc::doesCheckSumMatch()`.

La además provee la clase `CheckSum` para realizar el cálculo del dígito verificador de un RFC.
Cabe mencionar que, si bien debería ser siempre coincidente, hay algunos casos donde esto
no se respeta (SAT, ¿todo bien?), por ejemplo, el caso de *Restaurantes TOKS* ha tenido
los RFC `RT0840921REA` (dígito `A`) y también `RT0840921RE4`.

Puede ver el procedimiento del dígito verificador en alguno de estos enlaces:

- <https://www.studocu.com/es-mx/document/universidad-del-valle-de-mexico/administracion/algoritmo-para-generar-el-rfc-con-homoclave-para-personas-fisicas-y-morales/12002840>
- <https://solucionfactible.com/sfic/capitulos/timbrado/rfc-digito-verificador.jsp>

## Desarrollo

Para entender esta librería en el ámbito de desarrollo (para extender o modificar), lee los siguientes documentos:

- [Revisa la guía de contribución][contributing].
- [Consideraciones generales de la librería](develop/Generales.md).
- [Conversión de un RFC a entero y viceversa](develop/ConversionEntero.md).
- [Generador de RFC ficticios](develop/RfcFaker.md).

## Soporte

Puedes obtener soporte abriendo un ticker en Github.

Adicionalmente, esta librería pertenece a la comunidad [PhpCfdi](https://www.phpcfdi.com), así que puedes usar los
mismos canales de comunicación para obtener ayuda de algún miembro de la comunidad.

## Compatibilidad

Esta librería se mantendrá compatible con al menos la versión con
[soporte activo de PHP](https://www.php.net/supported-versions.php) más reciente.

También utilizamos [Versionado Semántico 2.0.0](docs/SEMVER.md) por lo que puedes
usar esta librería sin temor a romper tu aplicación.

## Contribuciones

Las contribuciones con bienvenidas. Por favor lee [CONTRIBUTING][] para más detalles
y recuerda revisar el archivo de tareas pendientes [TODO][] y el archivo [CHANGELOG][].

## Copyright and License

The `phpcfdi/rfc` library is copyright © [PhpCfdi](https://www.phpcfdi.com/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

[contributing]: https://github.com/phpcfdi/rfc/blob/main/CONTRIBUTING.md
[changelog]: https://github.com/phpcfdi/rfc/blob/main/docs/CHANGELOG.md
[todo]: https://github.com/phpcfdi/rfc/blob/main/docs/TODO.md

[source]: https://github.com/phpcfdi/rfc
[php-version]: https://packagist.org/packages/phpcfdi/rfc
[discord]: https://discord.gg/aFGYXvX
[release]: https://github.com/phpcfdi/rfc/releases
[license]: https://github.com/phpcfdi/rfc/blob/main/LICENSE
[build]: https://github.com/phpcfdi/rfc/actions/workflows/build.yml?query=branch:main
[reliability]:https://sonarcloud.io/component_measures?id=phpcfdi_rfc&metric=Reliability
[maintainability]: https://sonarcloud.io/component_measures?id=phpcfdi_rfc&metric=Maintainability
[coverage]: https://sonarcloud.io/component_measures?id=phpcfdi_rfc&metric=Coverage
[violations]: https://sonarcloud.io/project/issues?id=phpcfdi_rfc&resolved=false
[downloads]: https://packagist.org/packages/phpcfdi/rfc

[badge-source]: https://img.shields.io/badge/source-phpcfdi/rfc-blue?logo=github
[badge-discord]: https://img.shields.io/discord/459860554090283019?logo=discord
[badge-php-version]: https://img.shields.io/packagist/php-v/phpcfdi/rfc?logo=php
[badge-release]: https://img.shields.io/github/release/phpcfdi/rfc?logo=git
[badge-license]: https://img.shields.io/github/license/phpcfdi/rfc?logo=open-source-initiative
[badge-build]: https://img.shields.io/github/actions/workflow/status/phpcfdi/rfc/build.yml?branch=main&logo=github-actions
[badge-reliability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_rfc&metric=reliability_rating
[badge-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_rfc&metric=sqale_rating
[badge-coverage]: https://img.shields.io/sonar/coverage/phpcfdi_rfc/main?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-violations]: https://img.shields.io/sonar/violations/phpcfdi_rfc/main?format=long&logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/rfc?logo=packagist
