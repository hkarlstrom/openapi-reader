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

class JsonReader
{
    private $raw;

    public function __construct(string $jsonFilePath)
    {
        if (!file_exists($jsonFilePath)) {
            throw new Exception('JSON file ('.$jsonFilePath.') does not exist');
        }
        $this->raw = json_decode(file_get_contents($jsonFilePath), true);
    }

    public function get($path)
    {
        if (is_string($path)) {
            $path = explode('/', str_replace('#/', '', $path));
        }
        $json = $this->resolve($path);
        return is_array($json) ? $this->extendRef($json) : $json;
    }

    private function resolve(array $path)
    {
        $json = $this->raw;
        while (count($path)) {
            $key = array_shift($path);
            if (!isset($json[$key])) {
                return null;
            }
            $json = $json[$key];
        }
        return $json;
    }

    private function extendRef($json) : array
    {
        $ref     = '$'.'ref';
        $retJson = [];
        foreach ($json as $attr => $value) {
            if (is_array($value) && $value != []) {
                $retJson[$attr] = $this->extendRef($value);
            } elseif ($attr === $ref) {
                $refJson = $this->get($value);
                foreach ($refJson as $refAttr => $refValue) {
                    $retJson[$refAttr] = $refValue;
                }
            } else {
                $retJson[$attr] = $value;
            }
        }
        return $retJson;
    }
}
