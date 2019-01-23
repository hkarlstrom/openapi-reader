<?php

/**
 * OpenAPI Reader.
 *
 * @see       https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader\Tests;

use HKarlstrom\OpenApiReader\OpenApiReader;

class OpenApiReaderFromYamlTest extends OpenApiReaderTestBase
{
    protected function getReader(string $filename = 'testopenapi') : OpenApiReader
    {
        return new OpenApiReader(__DIR__.'/'.$filename.'.yaml');
    }
}
