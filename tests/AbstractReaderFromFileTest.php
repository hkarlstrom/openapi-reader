<?php
declare(strict_types=1);

namespace HKarlstrom\OpenApiReader\Tests;

use HKarlstrom\OpenApiReader\AbstractReader;
use HKarlstrom\OpenApiReader\JsonReader;
use HKarlstrom\OpenApiReader\YamlReader;
use PHPUnit\Framework\TestCase;

class AbstractReaderFromFileTest extends TestCase
{
    public function testCreateFromJson(): void
    {
        $reader = AbstractReader::fromFile(__DIR__ . '/testopenapi.json');

        $this->assertInstanceOf(JsonReader::class, $reader);
    }

    public function testCreateFromYaml(): void
    {
        $reader = AbstractReader::fromFile(__DIR__ . '/testopenapi.yaml');

        $this->assertInstanceOf(YamlReader::class, $reader);
    }
}
