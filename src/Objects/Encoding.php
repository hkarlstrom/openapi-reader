<?php

/**
 * OpenAPI Reader.
 *
 * @see       https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader\Objects;

class Encoding
{
    public $property;
    public $contentType  = [];
    public $contentTypes = [];
    public $headers      = [];
    public $style;
    public $explode;
    public $allowReserved;

    public function __construct(string $property, array $args)
    {
        $this->property      = $property;
        $this->contentType   = mb_strtolower($args['contentType']);
        $this->contentTypes  = preg_split('/\s*[;,]\s*/', $args['contentType']);
        $this->headers       = [];
        $this->style         = $args['style'] ?? null;
        $this->explode       = 'form' === $this->style;
        $this->allowReserved = $args['allowReserved'] ?? false;
    }

    public function hasContentType(string $contentType) : bool
    {
        return in_array($contentType, $this->contentTypes);
    }
}
