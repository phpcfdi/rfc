<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc\Exceptions;

use Exception;

final class InvalidIntegerToConvertException extends Exception implements RfcException
{
    private int $value;

    private function __construct(string $message, int $value)
    {
        parent::__construct($message);
        $this->value = $value;
    }

    public static function lowerThanZero(int $value): self
    {
        return new self('The integer serial number is lower than zero', $value);
    }

    public static function greaterThanMaximum(int $value): self
    {
        return new self('The integer serial number is greater than maximum value', $value);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
