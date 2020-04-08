<?php
/**
 * @noinspection PhpDocMissingThrowsInspection
 * @noinspection PhpUnhandledExceptionInspection
 */

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit;

use PhpCfdi\Rfc\Exceptions\InvalidIntegerToConvertException;
use PhpCfdi\Rfc\Rfc;
use PhpCfdi\Rfc\RfcIntConverter;
use PHPUnit\Framework\TestCase;

final class RfcIntConverterTest extends TestCase
{
    public function testConverterZeroToString(): void
    {
        $converter = new RfcIntConverter();
        $converted = $converter->intToString(0);
        $this->assertSame('AAA000101000', $converted);
    }

    public function testConverterMaxIntegerToString(): void
    {
        $converter = new RfcIntConverter();
        $converted = $converter->intToString(RfcIntConverter::MAX_INTEGER_VALUE);
        $this->assertSame('ÑÑÑÑ991231ZZA', $converted);
    }

    public function testConverterStringToZero(): void
    {
        $converter = new RfcIntConverter();
        $converted = $converter->stringToInt('AAA000101000');
        $this->assertSame(0, $converted);
    }

    public function testConverterStringToMaxInteger(): void
    {
        $converter = new RfcIntConverter();
        $converted = $converter->stringToInt('ÑÑÑÑ991231ZZA');
        $this->assertSame(RfcIntConverter::MAX_INTEGER_VALUE, $converted);
    }

    /**
     * @param int $inputSerial
     * @param string $inputRfc
     * @testWith [40270344269627, "COSC8001137NA"]
     * @testWith [ 1348025748541,  "DIM8701081LA"]
     */
    public function testKnownValues(int $inputSerial, string $inputRfc): void
    {
        $converter = new RfcIntConverter();
        $this->assertSame($inputRfc, $converter->intToString($inputSerial));
        $this->assertSame($inputSerial, $converter->stringToInt($inputRfc));
    }

    public function testThrowExceptionUsingIntegerLowerThanZero(): void
    {
        $this->expectException(InvalidIntegerToConvertException::class);
        $converter = new RfcIntConverter();
        $converter->intToString(-1);
    }

    public function testThrowExceptionUsingIntegersGreaterThanMaximum(): void
    {
        $this->expectException(InvalidIntegerToConvertException::class);
        $converter = new RfcIntConverter();
        $converter->intToString(RfcIntConverter::MAX_INTEGER_VALUE + 1);
    }

    public function testRfcRfcPersonaMoralPersonaFisicaBounds(): void
    {
        $moral = Rfc::fromSerial(RfcIntConverter::MORAL_UPPER_BOUND);
        $this->assertTrue($moral->isMoral());
        $fisica = Rfc::fromSerial(RfcIntConverter::FISICA_LOWER_BOUND);
        $this->assertTrue($fisica->isFisica());

        $this->assertSame(RfcIntConverter::FISICA_LOWER_BOUND, RfcIntConverter::MORAL_UPPER_BOUND + 1);
        $this->assertSame(RfcIntConverter::MAX_INTEGER_VALUE, RfcIntConverter::FISICA_UPPER_BOUND);
    }
}
