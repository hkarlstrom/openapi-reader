<?php

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
