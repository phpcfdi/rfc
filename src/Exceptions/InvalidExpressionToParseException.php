<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc\Exceptions;

use Exception;

final class InvalidExpressionToParseException extends Exception implements RfcException
{
    /** @var string */
    private $rfc;

    private function __construct(string $message, string $rfc)
    {
        parent::__construct($message);
        $this->rfc = $rfc;
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
