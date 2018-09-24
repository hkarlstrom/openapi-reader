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

class MediaType
{
    public $type;
    public $schema;
    public $example;
    public $examples = [];
    public $encoding = [];

    public function __construct(string $type, array $args)
    {
        $this->type     = $type;
        $this->schema   = $args['schema']   ?? null;
        $this->example  = $args['example']  ?? null;
        $this->examples = $args['examples'] ?? null;
        foreach ($args['encoding'] ?? [] as $property => $encodingArgs) {
            $this->encoding[$property] = new Encoding($property, $encodingArgs);
        }
    }

    public function getExample(string $type = null) : ?array
    {
        return $this->examples[$type] ?? $this->example;
    }
}
