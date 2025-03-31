<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

use DateTime;

/**
 * RfcIntConverter is a helper class to convert from an integer to RFC and backwards.
 * It should be used with well known string expressions: upper case and be valid according to RFC rules.
 *
 * The integer value is a 64-bit integer, goes from 0 to 332,162,701,516,799.
 * It can be stored in a PHP integer, mysql big int, sqlite int, etc.
 *
 * The way to transform the string to integer is splitting the contents into 9 parts with different bases:
 * | optional name | 3 x required name | day since 2000 | 2 x homoclave | checksum |
 * | base 29       | base 28           | base    36525  | base 36       | base 11  |
 * Rfc COSC8001137NA will be: [3, 14, 18, 2, 416731392, 33, 23, 10] => 40,270,344,269,627
 * To transform from the integer representation it get modulus for each base and retrieve the 9 integer parts:
 * 40,270,344,269,627 will be: [3, 14, 18, 2, 416731392, 33, 23, 10], then will convert each part to its strings.
 */
final class RfcIntConverter
{
    public const MIN_INTEGER_VALUE = 0;

    public const MAX_INTEGER_VALUE = 331482040243200 - 1; // EXP[last] * BASE[last]

    public const MORAL_LOWER_BOUND = self::MIN_INTEGER_VALUE;

    public const MORAL_UPPER_BOUND = self::FISICA_LOWER_BOUND - 1; // EXP[last] - 1

    public const FISICA_LOWER_BOUND = 11430415180800; // EXP[last]

    public const FISICA_UPPER_BOUND = self::MAX_INTEGER_VALUE; // EXP[last]

    private const BASES = [11, 36, 36, 36525, 28, 28, 28, 29];

    private const EXP = [1, 11, 396, 14256, 520700400, 14579611200, 408229113600, 11430415180800];

    private const CSUM_INT_CHAR = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A'];

    private const CSUM_CHAR_INT = [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 'A' => 10];

    private const HKEY_INT_CHAR = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    private const HKEY_CHAR_INT = [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28, 'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34, 'Z' => 35];

    private const NAME_REQ_INT_CHAR = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '&', '#'];

    private const NAME_REQ_CHAR_INT = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6, 'H' => 7, 'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 'Z' => 25, '&' => 26, '#' => 27];

    private const NAME_OPT_INT_CHAR = ['_', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '&', '#'];

    private const NAME_OPT_CHAR_INT = ['_' => 0, 'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13, 'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26, '&' => 27, '#' => 28];

    /**
     * Convert a valid uppercase RFC string expression to integer.
     *
     * Be aware that if you provide malformed RFC will return an integer but it might not be able to convert it back.
     */
    public function stringToInt(string $rfc): int
    {
        $string = str_pad(str_replace('Ñ', '#', $rfc), 13, '_', STR_PAD_LEFT);
        $integers = [
            self::EXP[0] * self::CSUM_CHAR_INT[$string[12]],
            self::EXP[1] * self::HKEY_CHAR_INT[$string[11]],
            self::EXP[2] * self::HKEY_CHAR_INT[$string[10]],
            self::EXP[3] * $this->strDateToInt(substr($string, 4, 6)),
            self::EXP[4] * self::NAME_REQ_CHAR_INT[$string[3]],
            self::EXP[5] * self::NAME_REQ_CHAR_INT[$string[2]],
            self::EXP[6] * self::NAME_REQ_CHAR_INT[$string[1]],
            self::EXP[7] * self::NAME_OPT_CHAR_INT[$string[0]],
        ];
        return intval(array_sum($integers));
    }

    /**
     * Convert an integer expression to a valid RFC string expression.
     *
     * @throws Exceptions\InvalidIntegerToConvertException if value is lower than zero or greater than maximum value
     */
    public function intToString(int $value): string
    {
        if ($value < 0) {
            throw Exceptions\InvalidIntegerToConvertException::lowerThanZero($value);
        }
        if ($value > self::MAX_INTEGER_VALUE) {
            throw Exceptions\InvalidIntegerToConvertException::greaterThanMaximum($value);
        }
        $integers = [];
        foreach (self::BASES as $base) {
            $integer = $value % $base;
            $value = intval(($value - $integer) / $base);
            $integers[] = $integer;
        }
        $strings = [
            self::NAME_OPT_INT_CHAR[$integers[7]],
            self::NAME_REQ_INT_CHAR[$integers[6]],
            self::NAME_REQ_INT_CHAR[$integers[5]],
            self::NAME_REQ_INT_CHAR[$integers[4]],
            $this->intTostrDate($integers[3]),
            self::HKEY_INT_CHAR[$integers[2]],
            self::HKEY_INT_CHAR[$integers[1]],
            self::CSUM_INT_CHAR[$integers[0]],
        ];
        return str_replace(['_', '#'], ['', 'Ñ'], implode('', $strings));
    }

    private function strDateToInt(string $value): int
    {
        /** @var DateTime $valueDate phpstan knows that createFromFormat can return false, this is not the case */
        $valueDate = DateTime::createFromFormat('Ymd', '20' . $value);
        return intval((new DateTime('2000-01-01'))->diff($valueDate)->days);
    }

    private function intTostrDate(int $value): string
    {
        return (new DateTime('2000-01-01'))->modify("+ $value days")->format('ymd');
    }
}
