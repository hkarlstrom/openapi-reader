<?php

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
        $contentTypeParts    = preg_split('/\s*[;,]\s*/', $args['contentType']);
        $this->contentType   = mb_strtolower($contentTypeParts[0]);
        $this->contentTypes  = explode(',', str_replace(' ', '', $this->contentType));
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
