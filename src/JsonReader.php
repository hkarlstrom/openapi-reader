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
    private $cache;

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
            $cacheKey = $path;
            if (isset($this->cache[$cacheKey])) {
                return $this->cache[$cacheKey];
            }
            $path = explode('/', str_replace('#/', '', $path));
        } else {
            $cacheKey = '#/'.implode('/', $path);
            if (isset($this->cache[$cacheKey])) {
                return $this->cache[$cacheKey];
            }
        }
        $json                          = $this->resolve($path);
        return $this->cache[$cacheKey] = is_array($json) ? $this->extendRef($json) : $json;
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
                if (!is_array($refJson)) {
                    throw new \Exception('Invalid ref: '.$value);
                }
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
