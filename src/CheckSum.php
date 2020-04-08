<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

final class CheckSum
{
    private const DICTIONARY = [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, '&' => 24, 'O' => 25, 'P' => 26, 'Q' => 27, 'R' => 28, 'S' => 29, 'T' => 30, 'U' => 31, 'V' => 32, 'W' => 33, 'X' => 34, 'Y' => 35, 'Z' => 36, ' ' => 37, '#' => 38];

    public function calculate(string $rfc): string
    {
        // 'Ñ' translated to '#' because 'Ñ' is multibyte 0xC3 0xB1
        $chars = str_split(str_replace('Ñ', '#', $rfc), 1);
        array_pop($chars); // remove predefined checksum
        $length = count($chars);
        $sum = (11 === $length) ? 481 : 0; // 481 para morales, 0 para físicas
        $j = $length + 1;
        foreach ($chars as $i => $char) {
            $sum += self::DICTIONARY[$char] * ($j - $i);
        }
        $digit = strval(11 - $sum % 11);
        if ('11' === $digit) {
            $digit = '0';
        } elseif ('10' === $digit) {
            $digit = 'A';
        }
        return $digit;
    }
}
