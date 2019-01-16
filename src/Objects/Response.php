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
    private $headers = [];
    private $content = [];

    public function __construct(string $statusCode, array $args)
    {
        $this->statusCode  = is_numeric($statusCode) ? intval($statusCode) : $statusCode;
        $this->description = $args['description'] ?? null;
        if (isset($args['headers'])) {
            foreach ($args['headers'] as $headerName => $headerArgs) {
                if ('content-type' !== mb_strtolower($headerName)) {
                    // If a response header is defined with the name "Content-Type", it SHALL be ignored.
                    // RFC7230 (https://tools.ietf.org/html/rfc7230#page-22) states header names are case insensitive
                    $this->headers[$headerName] = new Header($headerArgs);
                }
            }
        }
        foreach ($args['content'] ?? [] as $mediaType => $mediaTypeArgs) {
            $this->content[$mediaType] = new MediaType($mediaType, $mediaTypeArgs);
        }
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function getContent($mediaType = null) : ?MediaType
    {
        if (null === $mediaType) {
            $mediaType = $this->getDefaultMediaType();
        }
        return $this->content[$mediaType] ?? null;
    }

    public function getDefaultMediaType() : string
    {
        $mediaTypes = array_keys($this->content);
        return $mediaTypes[0];
    }
}
