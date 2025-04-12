<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit;

use PhpCfdi\Rfc\Exceptions\InvalidExpressionToParseException;
use PhpCfdi\Rfc\RfcParser;
use PhpCfdi\Rfc\Tests\TestCase;

final class RfcParserTest extends TestCase
{
    public function testParsePersonaFisica(): void
    {
        $parser = RfcParser::parse('COSC8001137NA');
        $this->assertSame('COSC', $parser->getName());
        $this->assertSame(80, $parser->getYear());
        $this->assertSame(1, $parser->getMonth());
        $this->assertSame(13, $parser->getDay());
        $this->assertSame('7N', $parser->getHkey());
        $this->assertSame('A', $parser->getChecksum());
    }

    public function testParsePersonaMoral(): void
    {
        $parser = RfcParser::parse('AAA99123103A');
        $this->assertSame('AAA', $parser->getName());
        $this->assertSame(99, $parser->getYear());
        $this->assertSame(12, $parser->getMonth());
        $this->assertSame(31, $parser->getDay());
        $this->assertSame('03', $parser->getHkey());
        $this->assertSame('A', $parser->getChecksum());
    }

    public function testParseUsingLowerCase(): void
    {
        $parser = RfcParser::parse('cosc8001137na');
        $this->assertSame('COSC', $parser->getName());
        $this->assertSame('7N', $parser->getHkey());
        $this->assertSame('A', $parser->getChecksum());
    }

    public function testParseUsingMultiByte(): void
    {
        $parser = RfcParser::parse('ÑÑÑÑ000101AAA');
        $this->assertSame('ÑÑÑÑ', $parser->getName());
    }

    public function testParseUsingLeapYear(): void
    {
        $parser = RfcParser::parse('AAAA000229AAA');
        $this->assertSame(0, $parser->getYear());
        $this->assertSame(2, $parser->getMonth());
        $this->assertSame(29, $parser->getDay());
    }

    /**
     * @throws InvalidExpressionToParseException
     * @testWith [""]
     *           ["AAA-010101AAA"]
     *           ["ÁAAA010101AAA"]
     *           ["AAAA010101AA"]
     *           ["AA010101AAA"]
     */
    public function testParseUsingInvalidExpressions(string $value): void
    {
        $this->expectException(InvalidExpressionToParseException::class);
        RfcParser::parse($value);
    }

    /**
     * @throws InvalidExpressionToParseException
     * @testWith ["AAAA010229AAA"]
     *           ["AAAA010132AAA"]
     */
    public function testParseUsingInvalidDates(string $value): void
    {
        $this->expectException(InvalidExpressionToParseException::class);
        $this->expectExceptionMessage('date');
        RfcParser::parse($value);
    }
}
