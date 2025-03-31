<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc\Exceptions;

use Exception;

final class InvalidExpressionToParseException extends Exception implements RfcException
{
    private function __construct(string $message, private readonly string $rfc)
    {
        parent::__construct($message);
    }

    public static function invalidParts(string $rfc): self
    {
        return new self('The RFC expression does not contain the valid parts', $rfc);
    }

    public static function invalidDate(string $rfc): self
    {
        return new self('The RFC expression does not contain a valid date', $rfc);
    }

    public function getRfc(): string
    {
        return $this->rfc;
    }
}
