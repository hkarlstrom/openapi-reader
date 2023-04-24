<?php

/**
 * OpenAPI Reader.
 *
 * @see       https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */
namespace HKarlstrom\OpenApiReader;

use Rize\UriTemplate;

class OpenApiReader
{
    /** @var string[] */
    private static $methods = [
        'get',
        'put',
        'post',
        'delete',
        'options',
        'head',
        'patch',
        'trace',
    ];

    /** @var AbstractReader */
    private $reader;

    /**
     * @property string|array $openApiSchema
     * @throws \Exception If the OpenAPI format not supported.
     */
    public function __construct($openApiSchema)
    {
        if (is_string($openApiSchema)) {
            $this->reader = AbstractReader::fromFile($openApiSchema);
        } elseif (is_array($openApiSchema)) {
            $this->reader = AbstractReader::fromArray($openApiSchema);
        } else {
            throw new \Exception('OpenAPI must be a file name or an array');
        }
    }

    public function getVersion() : string
    {
        return $this->reader->get('openapi');
    }

    public function getInfo() : Objects\Info
    {
        return new Objects\Info($this->reader->get('info'));
    }

    public function getPaths() : array
    {
        return $this->reader->get('paths') ?? [];
    }

    public function getPathsList() : array
    {
        return array_keys($this->reader->get('paths') ?? []);
    }

    public function getPathFromUri(string $uri, string $method, array &$parameters = []) : ?string
    {
        $parsed  = parse_url($uri);
        if ($matched = $this->matchPath($parsed['path'], $method, $parameters)) {
            return $matched;
        }
        $parts = explode('/', $parsed['path']);
        array_shift($parts);
        array_shift($parts);
        while (!empty($parts) && false === mb_strpos($parts[0], '{')) {
            if ($matched = $this->matchPath('/'.implode('/', $parts), $method, $parameters)) {
                return $matched;
            }
            array_shift($parts);
        }

        return null;
    }

    /**
     * @return HKarlstrom\OpenApiReader\Objects\Server[]
     */
    public function getServers() : array
    {
        $servers = [];
        foreach ($this->reader->get(['servers']) as $args) {
            $servers[] = new Objects\Server($args);
        }
        return $servers;
    }

    public function getSecurityScheme(string $securitySchemeName) : ?Objects\SecurityScheme
    {
        $data = $this->reader->get(['components', 'securitySchemes', $securitySchemeName]);
        if (!$data) {
            return null;
        }
        $securityScheme = new Objects\SecurityScheme();
        foreach ($data as $key => $value) {
            $securityScheme->$key = $value;
        }
        return $securityScheme;
    }

    public function getOperationSecurity(string $path, string $operation) : array
    {
        return $this->reader->get(['paths', $path, $operation, 'security']) ?? $this->reader->get(['security']) ?? [];
    }

    public function getOperationParameters(string $path, string $operation) : array
    {
        $parameters          = [];
        $operationParameters = $this->reader->get(['paths', $path, 'parameters']) ?? [];
        $pathParameters      = $this->reader->get(['paths', $path, $operation, 'parameters']) ?? [];
        foreach (array_merge($operationParameters, $pathParameters) as $p) {
            $parameters[] = new Objects\Parameter($p);
        }
        return $parameters;
    }

    public function getOperationRequestBody(string $path, string $operation) : ?Objects\RequestBody
    {
        $requestBody = $this->reader->get(['paths', $path, $operation, 'requestBody']);
        if (null != $requestBody) {
            return new Objects\RequestBody($requestBody);
        }
        return null;
    }

    public function getOperationResponse(string $path, string $operation, $statusCode = null) : ?Objects\Response
    {
        if (null === $statusCode) {
            $statusCode = $this->getOperationResponseCodes($path, $operation)[0];
        }
        $args = $this->reader->get(['paths', $path, $operation, 'responses', $statusCode]);
        return null === $args ? null : new Objects\Response($statusCode, $args);
    }

    public function getOperationResponseCodes(string $path, string $operation) : array
    {
        $codes = array_keys($this->reader->get(['paths', $path, $operation, 'responses']));
        foreach ($codes as &$code) {
            if (is_numeric($code)) {
                $code = intval($code);
            }
        }
        return $codes;
    }

    private function matchPath(string $uri, string $method, array &$parameters) : ?string
    {
        foreach ($this->reader->get('paths') as $path => $pathObject) {
            if (isset($pathObject[$method])) {
                if ($uri === $path) {
                    return $path;
                }
                $params = (new UriTemplate())->extract($path, $uri, true);
                if (null !== $params) {
                    $parameters = $params;
                    return $path;
                }
            }
        }

        return null;
    }
}
