<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc;

use JsonSerializable;
use Stringable;

/**
 * Value object representation of an RFC.
 */
final class Rfc implements JsonSerializable, Stringable
{
    /**
     * Generic representation of RFC (some use cases include to invoice without RFC)
     * @var string
     */
    public const RFC_GENERIC = 'XAXX010101000';

    /**
     * Foreign representation of RFC (used on foreign parties that does not have mexican RFC)
     * @var string
     */
    public const RFC_FOREIGN = 'XEXX010101000';

    private readonly int $length;

    /** @var string|null contains calculated checksum */
    private ?string $checkSum = null;

    /** @var int|null contains calculated integer representation */
    private ?int $serial = null;

    private function __construct(private string $rfc)
    {
        $this->length = mb_strlen($this->rfc);
    }

    /**
     * Parse a string and return a new Rfc instance, otherwise will throw an exception.
     *
     * @throws Exceptions\InvalidExpressionToParseException
     */
    public static function parse(string $rfc): self
    {
        $parts = RfcParser::parse($rfc);
        $rfc = sprintf(
            '%s%02d%02d%02d%s%s',
            $parts->getName(),
            $parts->getYear(),
            $parts->getMonth(),
            $parts->getDay(),
            $parts->getHkey(),
            $parts->getChecksum(),
        );
        return new self($rfc);
    }

    /**
     * Parse a string, if unable to parse will return NULL.
     *
     * @return self|null
     */
    public static function parseOrNull(string $rfc): ?self
    {
        try {
            return self::parse($rfc);
        } catch (Exceptions\InvalidExpressionToParseException) {
            return null;
        }
    }

    /**
     * Method to create the object if and only you already thrust the contents.
     */
    public static function unparsed(string $rfc): self
    {
        return new self($rfc);
    }

    /**
     * Create a Rfc object based on its numeric representation.
     *
     * @throws Exceptions\InvalidIntegerToConvertException
     */
    public static function fromSerial(int $serial): self
    {
        return new self((new RfcIntConverter())->intToString($serial));
    }

    public static function newGeneric(): self
    {
        return new self(self::RFC_GENERIC);
    }

    public static function newForeign(): self
    {
        return new self(self::RFC_FOREIGN);
    }

    /**
     * Return the rfc content, remember that it is a multi-byte string
     */
    public function getRfc(): string
    {
        return $this->rfc;
    }

    /**
     * Return TRUE if the RFC corresponds to a "Persona Física"
     */
    public function isFisica(): bool
    {
        return 13 === $this->length;
    }

    /**
     * Return TRUE if the RFC corresponds to a "Persona Moral"
     */
    public function isMoral(): bool
    {
        return 12 === $this->length;
    }

    /**
     * Returns TRUE if the Rfc corresponds to a generic local Rfc
     */
    public function isGeneric(): bool
    {
        return self::RFC_GENERIC === $this->rfc;
    }

    /**
     * Returns TRUE if the Rfc corresponds to a generic foreign Rfc
     */
    public function isForeign(): bool
    {
        return self::RFC_FOREIGN === $this->rfc;
    }

    /**
     * Calculates the checksum of the RFC.
     * Be aware that there are some valid RFC with invalid checksum.
     */
    public function calculateCheckSum(): string
    {
        if (null === $this->checkSum) {
            $this->checkSum = (new CheckSum())->calculate($this->getRfc());
        }
        return $this->checkSum;
    }

    /**
     * Return TRUE if the last character of the RFC is the same as the calculated checksum.
     * Be aware that there are some valid RFC with invalid checksum.
     */
    public function doesCheckSumMatch(): bool
    {
        return $this->calculateCheckSum() === $this->rfc[$this->length - 1];
    }

    /**
     * Calculates the serial number (integer representation) of the RFC
     */
    public function calculateSerial(): int
    {
        if (null === $this->serial) {
            $this->serial = (new RfcIntConverter())->stringToInt($this->getRfc());
        }
        return $this->serial;
    }

    public function __toString(): string
    {
        return $this->rfc;
    }

    public function jsonSerialize(): string
    {
        return $this->rfc;
    }
}
