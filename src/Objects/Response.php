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
        foreach ($args['headers'] as $name => $headerArgs) {
            if ('Content-Type' === $name) {
                // If a response header is defined with the name "Content-Type", it SHALL be ignored.
                continue;
            }
            // RFC7230 (https://tools.ietf.org/html/rfc7230#page-22) states header names are case insensitive
            $this->headers[mb_strtolower($name)] = new Header($headerArgs);
        }
        foreach ($args['content'] as $mediaType => $mediaTypeArgs) {
            $this->content[$mediaType] = new MediaType($mediaType, $mediaTypeArgs);
        }
    }

    public function getHeader($name) : ?Header
    {
        return $this->headers[mb_strtolower($name)] ?? null;
    }

    public function getHeaderNames() : array
    {
        return array_keys($this->headers);
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
