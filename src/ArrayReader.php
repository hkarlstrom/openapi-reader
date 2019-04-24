<?php

/**
 * OpenAPI Reader.
 *
 * @see       https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader;

class ArrayReader extends AbstractReader
{

    /**
     * ArrayReader constructor.
     *
     * @param array $schema
     */
    public function __construct(array $schema)
    {
        $this->raw = $schema;
    }

}
