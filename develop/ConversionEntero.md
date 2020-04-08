# Conversión de RFC a un número entero

La clave RFC se compone de 4 partes: `[Siglas] + [Fecha] + [Homoclave] + [Verificador]`.

Las `[Siglas]` son 4 o 3 caracteres dependiendo si se trata de una persona física (4) o moral (3).
Además dw que incluyen las letras, se incluye el `&` y `Ñ`, siendo la *eñe* un caracter de 2 bytes.

La `[Fecha]` es todo un tema, pues no usa 4 dígitos para el año, solo usa 2, luego entonces `000229` es inválido
para `1900-02-29` pero válido para `2000-02-29`.

La `[Homoclave]` en realidad incluye al dígito verificador, pero por el momento lo separaremos, básicamente porque
los caracteres que pueden incluirse son diferentes, en este caso se incluyen números y letras pero sin `&` ni `Ñ`.

El `[Verificador]` es un dígito calculado, pero no hay un estándar publicado bien definido, y he encontrado que
a pesar de las reglas para obtenerlo existen RFC que no cumplen (ni pueden cumplir) dicha regla, es como si el SAT
simplemente hubiera seguido con el siguiente dígito para un RFC de una misma entidad.
Los caracteres que lo componen son números y la letra `A`.

Tomando en cuenta las reglas anteriores, tenemos que un RFC como cadena de caracteres puede contener hasta 17 bytes,
y al momento de trabajar con un gran volúmen de información con este dato como índice pues podría ser bastante lento,
por ejemplo, en el caso de la lista de "RFC inscritos no cancelados".

Entonces, para resolver este problema, hice un convertidor que utiliza diferentes bases para ir y regresar de un entero.

## Conversión a bases

Primero se eliminan los multibytes y se acota el universo a sólo mayúsculas.
Con eso podemos entender las siguientes bases:

| Sigla opcional        | 3 Siglas obligatorias | Fecha                     | 2 Homoclave   | Verificador   |
| ---                   | ---                   | ---                       | ---           | ---           |
| `[A-Z, &, Ñ, <nada>]` | `[A-Z, &, Ñ]`         | Día desde `2000-01-01`    | `[0-9, A-Z]`  | `[0-9, A]`    |
| 29 opciones           | 28 opciones           | 36525 opciones            | 36 opciones   | 11 opciones   |

La fecha son 36525 opciones porque hay 25 años bisiestos, si se contara desde `1900-01-01` encontrarás que hay solo 24.

Dado lo anterior, se comporta como cualquier conversión de bases, con la salvedad de que cada grupo tiene un exponente
calculado en base a sus predecesores y no a su posición (porque las bases son diferentes).

Entonces, los exponentes por grupo son (opción previa × anterior):

- Verificador: `1`
- Homoclave 2: `11 = 11 × 1`
- Homoclave 1: `396 = 36 × 11 × 1`
- Fecha: `14,256 = 36 × 36 × 11 × 1`
- Sigla 4: `520,700,400 = 36,525 × 36 × 36 × 11 × 1`
- Sigla 3: `14,579,611,200 = 28 × 36,525 × 36 × 36 × 11 × 1`
- Sigla 2: `408,229,113,600 = 28 × 28 × 36,525 × 36 × 36 × 11 × 1`
- Sigla 1: `11,430,415,180,800 = 28 × 28 × 28 × 36,525 × 36 × 36 × 11 × 1`
- Máximas opciones: `331,482,040,243,200 = 29 × 28 × 28 × 28 × 36,525 × 36 × 36 × 11 × 1`

En fin, esto significa que existen `331,482,040,243,200` posibles claves RFC con las reglas actuales, por lo que
para PHP cabe en un entero de 64-bit y en una base de datos cabe como un `bigint` (8 bytes). Y eso es un ahorro
significativo para un índice de una base de datos, pudiendo ahora ser mucho más eficiente.

Para el RFC `COSC8001137NA` se tiene que hacer la conversión por cada grupo:

| Grupo   | N1   | N2   | N3   | N4   | Fecha         | H1   | H1   | V    |
| ---     | ---  | ---  | ---  | ---  | ---           | ---  | ---  | ---  |
| Valor   | `C`  | `O`  | `S`  | `C`  | `2080-01-13`  | `7`  | `N`  | `A`  | 
| Entrero | `3`  | `14` | `18` | `2`  | `29,232`      | `7`  | `23` | `10` |

Haciendo la suma de la multiplicación del valor entero por los exponentes, el número de serie es: `40,270,344,269,627`.

La primer `C` tiene un valor de `3` porque la primer opción es `0 => <vacío>`,
a diferencia de la segunda `C` con valor `2` porque en ese grupo el primer valor es `0 => A`.

Para regresar de la representación impresa se hace un ejercicio semejante pero utilizando el módulo según su base,
es decir, primero el módulo de `11`, después de `36`, y así hasta el módulo de `29`, y en cada iteración quitando
el valor previo del módulo por la base.

Con todo lo anterior, resulta que, el valor entero `0` equivale al RFC `AAA000101000`
y el valor máximo `331,482,040,243,199` equivale a `ÑÑÑÑ991231ZZA`.
Y cualquier clave RFC estará contenida en ese espacio numérico.  
