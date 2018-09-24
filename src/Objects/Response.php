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

class Response
{
    public $statusCode;
    public $description;
    public $headers;
    public $links;
    private $content = [];

    public function __construct(string $statusCode, array $args)
    {
        $this->statusCode  = is_numeric($statusCode) ? intval($statusCode) : $statusCode;
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
