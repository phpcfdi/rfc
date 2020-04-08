<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit\Exceptions;

use PhpCfdi\Rfc\Exceptions\InvalidExpressionToParseException;
use PhpCfdi\Rfc\Exceptions\RfcException;
use PHPUnit\Framework\TestCase;

final class InvalidExpressionToParseExceptionTest extends TestCase
{
    public function testExceptionInvalidParts(): void
    {
        $value = 'foo';
        $exception = InvalidExpressionToParseException::invalidParts($value);
        $this->assertInstanceOf(RfcException::class, $exception);
        $this->assertSame($value, $exception->getRfc());
        $this->assertStringContainsString('valid parts', $exception->getMessage());
    }

    public function testExceptionGreaterThanMaximum(): void
    {
        $value = 'foo';
        $exception = InvalidExpressionToParseException::invalidDate($value);
        $this->assertInstanceOf(RfcException::class, $exception);
        $this->assertSame($value, $exception->getRfc());
        $this->assertStringContainsString('valid date', $exception->getMessage());
    }
}
