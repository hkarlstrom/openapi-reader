<?php

/**
 * OpenAPI Reader.
 *
 * @see      https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader\Objects;

class Operation
{
    public $tags = [];
    public $summary;
    public $description;
    public $externalDocs;
    public $operationId;
    public $parameters = [];
    public $requestBody;
    public $responses = [];
    public $callbacks = [];
    public $deprecated;
    public $security = [];
    public $servers  = [];

    public function __construct(array $args)
    {
        $this->tags         = $args['tags']        ?? [];
        $this->summary      = $args['summary']     ?? null;
        $this->description  = $args['description'] ?? null;
        //$this->externalDocs = $args['externalDocs'] ?? null;
        $this->operationId  = $args['operationId'];
        //$this->requestBody  = $args['requestBody']  ?? null;
        //$this->responses    = $args['responses']    ?? null;
        //$this->callbacks    = $args['callbacks']    ?? null;
        $this->deprecated   = (bool) $args['deprecated'] ?? null;
        //$this->security     = $args['security']     ?? null;
        //$this->servers      = $args['servers']      ?? null;
    }
}
