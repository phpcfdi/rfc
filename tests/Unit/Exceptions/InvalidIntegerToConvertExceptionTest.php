<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit\Exceptions;

use PhpCfdi\Rfc\Exceptions\InvalidIntegerToConvertException;
use PhpCfdi\Rfc\Exceptions\RfcException;
use PhpCfdi\Rfc\Tests\TestCase;

final class InvalidIntegerToConvertExceptionTest extends TestCase
{
    public function testExceptionLowerThanZero(): void
    {
        $value = -1;
        $exception = InvalidIntegerToConvertException::lowerThanZero($value);
        $this->assertInstanceOf(RfcException::class, $exception);
        $this->assertSame($value, $exception->getValue());
        $this->assertStringContainsString('lower than zero', $exception->getMessage());
    }

    public function testExceptionGreaterThanMaximum(): void
    {
        $value = -1;
        $exception = InvalidIntegerToConvertException::greaterThanMaximum($value);
        $this->assertInstanceOf(RfcException::class, $exception);
        $this->assertSame($value, $exception->getValue());
        $this->assertStringContainsString('greater than maximum', $exception->getMessage());
    }
}
