<?php

namespace HKarlstrom\OpenApiReader;

function strEndsWith($haystack, $needle)
{
    $length = mb_strlen($needle);
    if (0 == $length) {
        return true;
    }
    return mb_substr($haystack, -$length) === $needle;
}

class OpenApiReader
{
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

    public function __construct(string $openApiFilePath)
    {
        $this->json = new JsonReader($openApiFilePath);
    }

    public function getVersion() : string
    {
        return $this->json->get('openapi');
    }

    public function getInfo() : Objects\Info
    {
        return new Objects\Info($this->json->get('info'));
    }

    public function getPathFromUri(string $uri, string $method, array &$parameters = []) : ?string
    {
        $parsed         = parse_url($uri);
        $templatePaths  = [];
        $exact          = null;
        foreach ($this->json->get('paths') as $path => $pathObject) {
            if (isset($pathObject[$method])) {
                if (false !== mb_strpos($path, '{')) {
                    $templatePaths[] = $path;
                } elseif (strEndsWith($parsed['path'], $path)
                    && (null === $exact || mb_strlen($path) < mb_strlen($exact))) {
                    $exact = $path;
                }
            }
        }
        $found = [];
        foreach ($templatePaths as $path) {
            $pattern = preg_replace_callback('/\/\{(.+?)\}/', function ($matches) {
                return '/(?<'.$matches[1].'>[^/}]+?)';
            }, $path);
            $pattern = '/'.str_replace('/', '\/', $pattern).'$/';
            if (preg_match_all($pattern, $parsed['path'], $matches)) {
                $params = [];
                foreach ($matches as $key => $values) {
                    if (!is_numeric($key)) {
                        $params[$key] = is_numeric($values[0]) ? intval($values[0]) : $values[0];
                    }
                }
                $found[$path] = $params;
            }
        }

        // If there is an exact path and template path matches, find out which one
        // has the most / chars, i.e. is the longest match
        $maxLength = mb_substr_count($exact ?? '', '/');
        $retPath   = $exact;
        foreach ($found as $path => $pathParameters) {
            $length = mb_substr_count($path, '/');
            if ($length > $maxLength) {
                $maxLength  = $length;
                $retPath    = $path;
                $parameters = $pathParameters;
            }
        }
        return $retPath;
    }

    /**
     * @return HKarlstrom\OpenApiReader\Objects\Server[]
     */
    public function getServers() : array
    {
        $servers = [];
        foreach ($this->json->get(['servers']) as $args) {
            $servers[] = new Objects\Server($args);
        }
        return $servers;
    }

    public function getSecurityScheme(string $securitySchemeName) : ?Objects\SecurityScheme
    {
        $data = $this->json->get(['components', 'securitySchemes', $securitySchemeName]);
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
        $securitySchemes            = [];
        $securityRequirements       = [];
        $securityRequirementObjects = $this->json->get(['paths', $path, $operation, 'security']) ?? $this->json->get(['security']) ?? [];
        foreach ($securityRequirementObjects as $securityRequirementObject) {
            $securityRequirements = array_merge($securityRequirements, $securityRequirementObject);
        }
        return $securityRequirements;
    }

    public function getOperationParameters(string $path, string $operation) : array
    {
        $parameters          = [];
        $operationParameters = $this->json->get(['paths', $path, 'parameters'])             ?? [];
        $pathParameters      = $this->json->get(['paths', $path, $operation, 'parameters']) ?? [];
        foreach (array_merge($operationParameters, $pathParameters) as $p) {
            $parameters[] = new Objects\Parameter($p);
        }
        return $parameters;
    }

    public function getOperationRequestBody(string $path, string $operation) : ?Objects\RequestBody
    {
        $requestBody = $this->json->get(['paths', $path, $operation, 'requestBody']);
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
        $args = $this->json->get(['paths', $path, $operation, 'responses', $statusCode]);
        return null === $args ? null : new Objects\Response($statusCode, $args);
    }

    public function getOperationResponseCodes(string $path, string $operation) : array
    {
        $codes = array_keys($this->json->get(['paths', $path, $operation, 'responses']));
        foreach ($codes as &$code) {
            if (is_numeric($code)) {
                $code = intval($code);
            }
        }
        return $codes;
    }
}
