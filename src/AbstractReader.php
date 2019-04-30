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

abstract class AbstractReader
{
    /**
     * @throws \Exception If the OpenAPI file is not a supported type.
     */
    public static function fromFile(string $openApiFilePath): AbstractReader
    {
        if (preg_match('/\.json$/', $openApiFilePath) === 1) {
            return new JsonReader($openApiFilePath);
        }

        if (preg_match('/\.ya?ml$/', $openApiFilePath) === 1) {
            return new YamlReader($openApiFilePath);
        }

        throw new \Exception('OpenAPI file name must have .json, .yaml or .yml extension');
    }

    public static function fromArray(array $openApiSchema): AbstractReader
    {
    	  return new ArrayReader($openApiSchema);
    }

    /** @var array */
    protected $raw;

    /** @var array */
    protected $cache;

    public function get($path)
    {
        if (is_string($path)) {
            $cacheKey = $path;
            if (isset($this->cache[$cacheKey])) {
                return $this->cache[$cacheKey];
            }
            $path = explode('/', str_replace('#/', '', $path));
            // Replace JSON encoded characters
            // https://tools.ietf.org/html/rfc6901
            foreach ($path as &$p) {
                $p = str_replace('~1', '/', $p);
                $p = str_replace('~0', '~', $p);
                $p = str_replace('%7B', '{', $p);
                $p = str_replace('%7D', '}', $p);
            }
        } else {
            $cacheKey = '#/'.implode('/', $path);
            if (isset($this->cache[$cacheKey])) {
                return $this->cache[$cacheKey];
            }
        }
        $data                          = $this->resolve($path);
        return $this->cache[$cacheKey] = is_array($data) ? $this->extendRef($data) : $data;
    }

    protected function resolve(array $path)
    {
        $data = $this->raw;
        while (count($path)) {
            $key = array_shift($path);
            if (!isset($data[$key])) {
                return null;
            }
            $data = $data[$key];
        }
        return $data;
    }

    protected function extendRef($data) : array
    {
        $ref     = '$'.'ref';
        $retData = [];
        foreach ($data as $attr => $value) {
            if (is_array($value) && $value != []) {
                $retData[$attr] = $this->extendRef($value);
            } elseif ($attr === $ref) {
                $refData = $this->get($value);
                if (!is_array($refData)) {
                    throw new \Exception('Invalid ref: '.$value);
                }
                foreach ($refData as $refAttr => $refValue) {
                    $retData[$refAttr] = $refValue;
                }
            } else {
                $retData[$attr] = $value;
            }
        }
        return $retData;
    }
}
