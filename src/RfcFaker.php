<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

/**
 * This class creates a random but syntactically valid Rfc string
 *
 * You can use it with `FakerPHP/Faker` library by registering into the faker generator:
 * ```php
 * $faker = new Faker\Generator();
 * $faker->addProvider(new PhpCfdi\Rfc\RfcFaker());
 * $rfc = $faker->mexicanRfc;
 * $rfcMoral = $faker->mexicanRfcMoral;
 * $rfcFisica = $faker->mexicanRfcFisica;
 * ```
 */
final class RfcFaker
{
    /**
     * Return an RFC for Persona Moral (12 multi-byte chars length) or Persona Fisica (13 multi-byte chars length)
     *
     * @example COSC8001137NA, EKU9003173C9
     */
    public function mexicanRfc(): string
    {
        // from moral to fisica, moral is lower than fisica
        return $this->privateMakeRfc(RfcIntConverter::MORAL_LOWER_BOUND, RfcIntConverter::FISICA_UPPER_BOUND);
    }

    /**
     * Return an RFC for Persona Moral (12 multi-byte chars length)
     *
     * @example EKU9003173C9
     */
    public function mexicanRfcMoral(): string
    {
        return $this->privateMakeRfc(RfcIntConverter::MORAL_LOWER_BOUND, RfcIntConverter::MORAL_UPPER_BOUND);
    }

    /**
     * Return an RFC for Persona Fisica (13 multi-byte chars length)
     *
     * @example COSC8001137NA
     */
    public function mexicanRfcFisica(): string
    {
        return $this->privateMakeRfc(RfcIntConverter::FISICA_LOWER_BOUND, RfcIntConverter::FISICA_UPPER_BOUND);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function privateMakeRfc(int $lowerBound, int $upperBound): string
    {
        $converter = new RfcIntConverter();
        $random = random_int($lowerBound, $upperBound);
        return $converter->intToString($random);
    }
}
