<?php

/**
 * OpenAPI Reader.
 *
 * @see      https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader\Objects;

class ServerVariable
{
    public $name;
    public $enum = null;
    public $default;
    public $description;

    public function __construct(string $name, array $args)
    {
        $this->name        = $name;
        $this->enum        = $args['args'] ?? null;
        $this->default     = $args['default'];
        $this->description = $args['description'] ?? null;
    }
}
