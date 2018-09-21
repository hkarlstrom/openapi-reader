<?php

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
