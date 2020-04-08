# Consideraciones de desarrollo generales de la librería

## Uso de `final`

No deberías tener ningún motivo para necesitar extender las clases que aquí de proponen.

Lo que te recomiendo es que utilices el patrón de diseño *Proxy*
<https://designpatternsphp.readthedocs.io/en/latest/Structural/Proxy/README.html>

## El constructor de `Rfc` es `private`

Partamos por los siguientes hechos:

- Un objeto creado debería tener un estado válido.
- Revisar si una clave RFC es válida es "costosa", se tiene que umplir una expresión regular compleja
  además de revisar que la fecha contenga un dato válido.

Por lo anterior, resulta costoso *siempre* tener que validar si una clave RFC es válida,
si el RFC viene de la conversión de un entero, entonces la clave será válida, no es necesario verificarla.

La clase `Rfc` tiene varios constructores estáticos: para construir el objeto validándolo, para construir el
objeto a partir de un RFC genérico nacional, o de extrangero, o a partir de un entero.

Así pues, en lugar de permitir `new Rfc(string)` para crear un el objeto con la clave RFC proporcionada,
preferí darle nombre como constructor estático `Rfc::unparsed(string): self` y así no caer en la falsa idea
de que el objeto iba a ser creado validando la clave de RFC usando `new`. Por otro lado, es mucho más explícito.
