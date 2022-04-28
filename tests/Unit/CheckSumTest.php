<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit;

use PhpCfdi\Rfc\CheckSum;
use PhpCfdi\Rfc\Tests\TestCase;

final class CheckSumTest extends TestCase
{
    /** @return array<string, array{string, string}> */
    public function providerCheckSum(): array
    {
        return [
            'física 0' => ['CAMA911215CJ0', '0'],
            'física A' => ['COSC8001137NA', 'A'],
            'física [1-9]' => ['SORC591116FJ6', '6'],

            'moral A' => ['DIM8701081LA', 'A'],
            'moral 0' => ['A&A050908GT0', '0'],
            'moral [1-9]' => ['SAT970701NN3', '3'],

            'multibyte' => ['AÑÑ801231JK0', '0'],

            'empty rfc' => ['', '0'],
            'invalid rfc' => ['$', '0'],
            'invalid chars' => ['AAA010101$$$', '7'], // $ is managed as 0
        ];
    }

    /** @dataProvider providerCheckSum */
    public function testCheckSum(string $rfc, string $expected): void
    {
        $checksum = new CheckSum();
        $this->assertSame($expected, $checksum->calculate($rfc));
    }
}
