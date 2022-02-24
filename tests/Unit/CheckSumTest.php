<?php

declare(strict_types=1);

namespace PhpCfdi\Rfc\Tests\Unit;

use PhpCfdi\Rfc\CheckSum;
use PhpCfdi\Rfc\Tests\TestCase;

final class CheckSumTest extends TestCase
{
    public function testCheckSum(): void
    {
        $expected = 'A';
        $rfc = 'COSC8001137NA';

        $checksum = new CheckSum();
        $this->assertSame($expected, $checksum->calculate($rfc));
    }

    public function testCheckSumWithMultiByte(): void
    {
        $expected = '0';
        $rfc = 'AÑÑ801231JK0';

        $checksum = new CheckSum();
        $this->assertSame($expected, $checksum->calculate($rfc));
    }
}
