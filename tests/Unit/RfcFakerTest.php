<?php

/**
 * @noinspection PhpDocMissingThrowsInspection
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit;

use PhpCfdi\Rfc\Rfc;
use PhpCfdi\Rfc\RfcFaker;
use PhpCfdi\Rfc\Tests\TestCase;

final class RfcFakerTest extends TestCase
{
    /** @var int The number of times to run fakes */
    private $iterations = 100;

    public function testMexicanRfc(): void
    {
        $faker = new RfcFaker();
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach (range(1, $this->iterations) as $i) {
            $strRfc = $faker->mexicanRfc();
            $this->assertNotNull(Rfc::parseOrNull($strRfc), "Cannot create an RFC from $strRfc");
        }
    }

    public function testMexicanRfcPersonaFisica(): void
    {
        $faker = new RfcFaker();
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach (range(1, $this->iterations) as $i) {
            $strRfc = $faker->mexicanRfcFisica();
            $rfc = Rfc::parse($strRfc);
            $this->assertTrue($rfc->isFisica());
        }
    }

    public function testMexicanRfcPersonaMoral(): void
    {
        $faker = new RfcFaker();
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach (range(1, $this->iterations) as $i) {
            $strRfc = $faker->mexicanRfcMoral();
            $rfc = Rfc::parse($strRfc);
            $this->assertTrue($rfc->isMoral());
        }
    }
}
