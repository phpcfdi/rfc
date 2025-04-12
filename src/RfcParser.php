<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

/**
 * RfcParser will parse a string representation into the different Rfc parts.
 */
final class RfcParser
{
    /**
     * @param string $name "siglas" part ____000101AAA
     * @param int $year "año" part AAAA__0101AAA
     * @param int $month "mes" part AAAA00__01AAA
     * @param int $day "día" part AAAA0001__AAA
     * @param string $hkey "homoclave" part AAAA000101__A
     * @param string $checksum "dígito verificador" part AAAA000101AA_
     */
    private function __construct(
        private readonly string $name,
        private readonly int $year,
        private readonly int $month,
        private readonly int $day,
        private readonly string $hkey,
        private readonly string $checksum,
    ) {
    }

    /**
     * @throws Exceptions\InvalidExpressionToParseException
     */
    public static function parse(string $rfc): self
    {
        /*
         * Explicación de la expresión regular:
         * - desde el inicio
         *      /^
         * - letras y números para el nombre (3 para morales, 4 para físicas)
         *      (?<name>[A-ZÑ&]{3,4})
         * - año mes y día, la validez de la fecha se comprueba después
         *      (?<year>\d{2})(?<month>\d{2})(?<day>\d{2})
         * - homoclave (letra o dígito 2 veces + A o dígito 1 vez)
         *      (?<hkey>[A-Z\d]{2})(?<checksum>[A\d])
         * - hasta el final
         *      $/
         * - tratamiento unicode
         *      u
         */
        $regex = '/^(?<name>[A-ZÑ&]{3,4})(?<year>\d{2})(?<month>\d{2})(?<day>\d{2})(?<hkey>[A-Z\d]{2})(?<checksum>[A\d])$/u';
        if (1 !== preg_match($regex, mb_strtoupper($rfc), $matches)) {
            throw Exceptions\InvalidExpressionToParseException::invalidParts($rfc);
        }

        $date = (int) strtotime(sprintf('20%s-%s-%s', $matches['year'], $matches['month'], $matches['day']));
        if ($matches['year'] . $matches['month'] . $matches['day'] !== date('ymd', $date)) {
            throw Exceptions\InvalidExpressionToParseException::invalidDate($rfc);
        }

        return new self(
            $matches['name'],
            (int) $matches['year'],
            (int) $matches['month'],
            (int) $matches['day'],
            $matches['hkey'],
            $matches['checksum'],
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getHkey(): string
    {
        return $this->hkey;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }
}
