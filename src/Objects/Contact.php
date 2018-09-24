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

class Contact
{
    public $name;
    public $url;
    public $email;

    public function __construct(array $args)
    {
        $this->name  = $args['name']  ?? null;
        $this->url   = $args['url']   ?? null;
        $this->email = $args['email'] ?? null;
    }
}
