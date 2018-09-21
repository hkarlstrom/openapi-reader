<?php

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
