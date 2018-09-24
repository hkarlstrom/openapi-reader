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

class License
{
    public $name;
    public $url;

    public function __construct(array $args)
    {
        $this->name = $args['name'];
        $this->url  = $args['url'] ?? null;
    }
}
