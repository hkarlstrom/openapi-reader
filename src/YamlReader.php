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

use Symfony\Component\Yaml\Yaml;

class YamlReader extends AbstractReader
{

    /**
     * YamlReader constructor.
     *
     * @param string $filePath
     *
     * @throws \Exception
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('YAML file ('.$filePath.') does not exist');
        }
        $this->raw = Yaml::parseFile($filePath);
    }

}
