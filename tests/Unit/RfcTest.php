<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit;

use PhpCfdi\Rfc\Exceptions\InvalidExpressionToParseException;
use PhpCfdi\Rfc\Rfc;
use PhpCfdi\Rfc\Tests\TestCase;

final class RfcTest extends TestCase
{
    public function testCreateRfcPersonaFisica(): void
    {
        $input = 'COSC8001137NA';
        $rfc = Rfc::unparsed($input);
        $this->assertSame($input, $rfc->getRfc());
        $this->assertSame($input, (string) $rfc);
        $this->assertFalse($rfc->isGeneric());
        $this->assertFalse($rfc->isForeign());
        $this->assertFalse($rfc->isMoral());
        $this->assertTrue($rfc->isFisica());
        $this->assertSame('A', $rfc->calculateCheckSum());
        $this->assertTrue($rfc->doesCheckSumMatch());
        $this->assertSame(40270344269627, $rfc->calculateSerial());
    }

    public function testCreateRfcMoral(): void
    {
        $input = 'DIM8701081LA';
        $rfc = Rfc::unparsed($input);
        $this->assertSame($input, $rfc->getRfc());
        $this->assertSame($input, (string) $rfc);
        $this->assertFalse($rfc->isGeneric());
        $this->assertFalse($rfc->isForeign());
        $this->assertTrue($rfc->isMoral());
        $this->assertFalse($rfc->isFisica());
        $this->assertSame('A', $rfc->calculateCheckSum());
        $this->assertTrue($rfc->doesCheckSumMatch());
        $this->assertSame(1348025748541, $rfc->calculateSerial());
    }

    public function testCreateWithForeign(): void
    {
        $rfc = Rfc::unparsed(Rfc::RFC_FOREIGN);
        $this->assertTrue($rfc->isForeign());
        $this->assertTrue($rfc->isFisica());
        $this->assertFalse($rfc->isGeneric());
        $this->assertFalse($rfc->isMoral());
    }

    public function testCreateWithGeneric(): void
    {
        $rfc = Rfc::unparsed(Rfc::RFC_GENERIC);
        $this->assertTrue($rfc->isGeneric());
        $this->assertTrue($rfc->isFisica());
        $this->assertFalse($rfc->isForeign());
        $this->assertFalse($rfc->isMoral());
    }

    public function testParse(): void
    {
        $rfc = Rfc::parse('COSC8001137NA');
        $this->assertSame('COSC8001137NA', (string) $rfc);
    }

    public function testParseError(): void
    {
        $this->expectException(InvalidExpressionToParseException::class);
        Rfc::parse('COSC800113-7NA');
    }

    public function testParseOrNull(): void
    {
        $this->assertNotNull(Rfc::parseOrNull('COSC8001137NA'));
        $this->assertNull(Rfc::parseOrNull(''));
    }

    public function testSerialNumber(): void
    {
        $rfc = Rfc::fromSerial(1348025748541);
        $this->assertSame(1348025748541, $rfc->calculateSerial());
        $this->assertSame('DIM8701081LA', $rfc->getRfc());
    }

    public function testCreateBadDigit(): void
    {
        $rfc = Rfc::parse('COSC8001137N9');
        $this->assertSame('A', $rfc->calculateCheckSum());
        $this->assertFalse($rfc->doesCheckSumMatch());
    }

    public function testWithMultiByte(): void
    {
        $rfcMultiByte = 'AñÑ801231JK0';
        $expected = 'AÑÑ801231JK0';

        $rfc = Rfc::parse($rfcMultiByte);
        $this->assertSame($expected, $rfc->getRfc());
    }

    public function testJsonSerialize(): void
    {
        $data = ['rfc' => Rfc::unparsed('COSC8001137NA')];
        $this->assertJsonStringEqualsJsonString(
            '{"rfc": "COSC8001137NA"}',
            json_encode($data) ?: '',
        );
    }

    public function testCreateGeneric(): void
    {
        $rfc = Rfc::newGeneric();
        $this->assertSame(Rfc::RFC_GENERIC, $rfc->getRfc());
    }

    public function testCreateForeign(): void
    {
        $rfc = Rfc::newForeign();
        $this->assertSame(Rfc::RFC_FOREIGN, $rfc->getRfc());
    }
}
