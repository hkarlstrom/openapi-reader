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

class ExternalDocumentation
{
    public $description;
    public $url;

    public function __construct(array $args)
    {
        $this->description = $args['description'] ?? null;
        $this->url         = $args['url'];
    }
}
