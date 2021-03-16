# Entorno de desarrollo

Para tener un entorno de desarrollo totalmente funcional considera los siguientes pasos:

- Obtén el proyecto

```
git clone https://github.com/phpcfdi/rfc.git
```

- Instala las dependencias

```
composer install
```

- Instala las dependencias de desarrollo

```
composer dev:install
```

Al hacer las modificaciones, considera estos comandos útiles

- Verificación de estilo de código

```
composer dev:check-style
```

- Corrección de estilo de código

```
composer dev:fix-style
```

- Ejecución de pruebas

```
composer dev:test
```

- Ejecución todo en uno: corregir estilo, verificar estilo y correr pruebas

```
composer dev:build
```
