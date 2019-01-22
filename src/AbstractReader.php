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
    protected $raw;
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
        $json                          = $this->resolve($path);
        return $this->cache[$cacheKey] = is_array($json) ? $this->extendRef($json) : $json;
    }

    protected function resolve(array $path)
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

    protected function extendRef($json) : array
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
