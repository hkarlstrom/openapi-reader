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

class RequestBody
{
    public $description;
    public $required;
    private $content = [];

    public function __construct(array $args)
    {
        $this->description = $args['description'] ?? null;
        foreach ($args['content'] as $mediaType => $mediaTypeArgs) {
            $this->content[$mediaType] = new MediaType($mediaType, $mediaTypeArgs);
        }
        $this->required = $args['required'] ?? false;
    }

    public function getContent($mediaType = null) : ?MediaType
    {
        return $this->content[$mediaType] ?? array_shift($this->content) ?? null;
    }
}
