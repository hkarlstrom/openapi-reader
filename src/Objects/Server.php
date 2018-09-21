<?php

namespace HKarlstrom\OpenApiReader\Objects;

class Server
{
    public $url;
    public $description;
    public $variables = [];

    public function __construct(array $args)
    {
        $this->url         = $args['url'];
        $this->description = $args['description'] ?? null;
        foreach ($args['variables'] ?? [] as $vArgs) {
            $variables[] = new HKarlstrom\OpenApiReader\Objects\ServerVariable($vArgs);
        }
    }
}
