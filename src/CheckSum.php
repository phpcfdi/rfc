<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

final class CheckSum
{
    private const DICTIONARY = [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, '&' => 24, 'O' => 25, 'P' => 26, 'Q' => 27, 'R' => 28, 'S' => 29, 'T' => 30, 'U' => 31, 'V' => 32, 'W' => 33, 'X' => 34, 'Y' => 35, 'Z' => 36, ' ' => 37, '#' => 38];

    private const DIGIT_OVERRIDE = [10 => 'A', 11 => '0'];

    public function calculate(string $rfc): string
    {
        // 'Ñ' cambia a '#' porque 'Ñ' es multi-byte 0xC3 0xB1
        $chars = str_split(str_replace('Ñ', '#', $rfc), 1);
        $length = count($chars);
        array_pop($chars); // remover el dígito predefinido

        // Valor inicial de la suma: 481 para morales, 0 para físicas
        $sum = (12 === $length) ? 481 : 0;
        // suma de valores: Σ(Vi * (Pi + 1))
        foreach ($chars as $i => $char) {
            $sum += (self::DICTIONARY[$char] ?? 0) * ($length - $i);
        }

        // posibles valores: [1, 2, ..., 10, 11] porque $sum % 11 => int<0, 10>
        $digit = 11 - $sum % 11;
        // se retorna 10 => 0, 11 => A o el valor obtenido
        return self::DIGIT_OVERRIDE[$digit] ?? strval($digit);
    }
}
