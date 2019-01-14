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

class Header
{
    public $description;
    public $required;
    public $deprecated;
    public $schema;

    public function __construct(array $args)
    {
        $this->description     = $args['description'] ?? null;
        $this->required        = $args['required']    ?? false;
        $this->deprecated      = $args['deprecated']  ?? false;
        if (isset($args['schema'])) {
            $this->schema = (object) $args['schema'];
        }
    }
}
