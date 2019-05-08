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

class JsonReader extends AbstractReader
{

    /**
     * JsonReader constructor.
     *
     * @param string $filePath
     *
     * @throws \Exception
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('JSON file ('.$filePath.') does not exist');
        }
        $this->raw = json_decode(file_get_contents($filePath), true);

        if (json_last_error() > 0) {
            throw new \Exception('JSON file ('.$filePath.') parsing failed: '.json_last_error_msg());
        }
    }
}
