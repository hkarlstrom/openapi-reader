<?php
declare(strict_types=1);

namespace HKarlstrom\OpenApiReader\Tests;

use HKarlstrom\OpenApiReader\JsonReader;
use PHPUnit\Framework\TestCase;

class JsonReaderTest extends TestCase
{
    public function testItThrowsOnInvalidJsonFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("invalid.json");

        new JsonReader(__DIR__.'/invalid.json');
    }
}
