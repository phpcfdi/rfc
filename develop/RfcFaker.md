# Generador de RFC ficticios

En algunas ocasiones puede resultar útil inventarse RFC, por ejemplo, al estar haciendo pruebas.

Para generar claves RFC inventadas (*fakes*) se puede utilizar la clase `RfcFaker` que puede crear un RFC
cualquiera `RfcFaker::mexicanRfc()` o de persona moral `RfcFaker::mexicanRfcMoral()`
o de persona física `RfcFaker::mexicanRfcFisica()`.

```php
$faker = new PhpCfdi\Rfc\RfcFaker();
$rfc = $faker->mexicanRfc();
$rfcMoral = $faker->mexicanRfcMoral();
$rfcFisica = $faker->mexicanRfcFisica();
```

La forma de crearlo es con un número aleatorio para personas morales, o bien para personas físicas,
o bien para todo el espectro.

## Integración con `fzaninotto/Faker`

También se puede usar ese mismo objeto dentro de la librería más común para generación de falsos
 en PHP [`fzaninotto/Faker`](https://github.com/fzaninotto/Faker).

```php
$faker = new Faker\Generator();
$faker->addProvider(new PhpCfdi\Rfc\RfcFaker());
$rfc = $faker->mexicanRfc;
$rfcMoral = $faker->mexicanRfcMoral;
$rfcFisica = $faker->mexicanRfcFisica;
``` 

## Integración con `Laravel`

Nota: Esta librería es agnóstica, este caso es solo para ilustrar cómo se puede integrar con ese framework.

Integrar con Laravel se podría hacer instruyendo al contenedor de laravel para que extienda `Faker\Generator`,
cuando sea creado. La instancia de `Faker\Generator` existe y es previamente definida por el propio framework
al ser parte de sus llamadas `factories`.

```
// File: app/Providers/AppServiceProvider.php

use Faker\Generator as FakerGenerator;

class AppServiceProvider extends ServiceProvider
{
    // ...
    public function register()
    {
        // ...
        $this->app->extend(FakerGenerator::class, function($generator, $app) {
            /** @var FakerGenerator $generator */
            $generator->addProvider(new RfcProvider());
            return $generator;
        });
        // ...
    }
    // ...
}
```
