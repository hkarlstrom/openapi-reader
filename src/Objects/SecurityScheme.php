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

class SecurityScheme
{
    public $type;
    public $description;
    public $name;
    public $in;
    public $scheme;
    public $bearerFormat;
    public $flows = [];
    public $openIdConnectUrl;
}
