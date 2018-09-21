<?php

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
