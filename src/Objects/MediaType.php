<?php

/**
 * OpenAPI Reader.
 * @see       https://github.com/hkarlstrom/openapi-reader
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader\Objects;

class MediaType
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var mixed|null
     */
    public $schema;

    /**
     * @var mixed|null
     */
    public $example;

    /**
     * @var array|mixed|null
     */
    public $examples = [];

    /**
     * @var array
     */
    public $encoding = [];

    /**
     * @param string $type
     * @param array  $args
     */
    public function __construct(string $type, array $args)
    {
        $this->type = $type;
        $this->schema = ($args['schema'] ?? null);
        $this->example = ($args['example'] ?? null);
        $this->examples = ($args['examples'] ?? null);

        foreach (($args['encoding'] ?? []) as $property => $encodingArgs)
        {
            $this->encoding[$property] = new Encoding($property, $encodingArgs);
        }
    }

    /**
     * @param string|null $type
     * @return array|null
     */
    public function getExample(?string $type = null): ?array
    {
        return ($this->examples[$type] ?? $this->example);
    }
}
