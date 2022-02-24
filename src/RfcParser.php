<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

/**
 * RfcParser will parse a string representation into the different Rfc parts.
 */
final class RfcParser
{
    /** @var string "siglas" part ____000101AAA */
    private $name;

    /** @var int "año" part AAAA__0101AAA */
    private $year;

    /** @var int "mes" part AAAA00__01AAA */
    private $month;

    /** @var int "día" part AAAA0001__AAA */
    private $day;

    /** @var string "homoclave" part AAAA000101__A */
    private $hkey;

    /** @var string "dígito verificador" part AAAA000101AA_ */
    private $checksum;

    private function __construct(string $name, int $year, int $month, int $day, string $hkey, string $checksum)
    {
        $this->name = $name;
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->hkey = $hkey;
        $this->checksum = $checksum;
    }

    /**
     * @param string $rfc
     * @return self
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
