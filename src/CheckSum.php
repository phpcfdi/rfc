<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

final class CheckSum
{
    private const DICTIONARY = '0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ #';

    public function calculate(string $rfc): string
    {
        // 'Ñ' translated to '#' because 'Ñ' is multibyte 0xC3 0xB1
        $chars = str_split(str_replace('Ñ', '#', $rfc));
        $length = count($chars);
        array_pop($chars); // remove predefined checksum
        $sum = (12 === $length) ? 481 : 0; // 481 para morales, 0 para físicas
        foreach ($chars as $i => $char) {
            $posChar = strpos(self::DICTIONARY, $char);
            $factor = $length - $i;
            $sum += $posChar * $factor;
        }
        $mod11 = $sum % 11;
        if ($mod11 === 0) {
          return '0';
        } else if($mod11 === 1) {
          return 'A';
        }
        return strval(11 - $mod11);
    }
}
