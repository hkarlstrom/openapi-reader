<?php

/**
 * OpenAPI Reader.
 *
 * @see       https://github.com/hkarlstrom/openapi-reader
 *
 * @copyright Copyright (c) 2018 Henrik Karlström
 * @license   MIT
 */

namespace HKarlstrom\OpenApiReader\Tests;

use HKarlstrom\OpenApiReader\OpenApiReader;
use PHPUnit\Framework\TestCase;

abstract class OpenApiReaderTestBase extends TestCase
{
    public function testVersion()
    {
        $openapi    = $this->getReader();
        $this->assertSame('3.0.0', $openapi->getVersion());
    }

    public function testInfo()
    {
        $openapi    = $this->getReader();
        $info       = $openapi->getInfo();
        $this->assertInstanceOf('HKarlstrom\OpenApiReader\Objects\Info', $info);
        $this->assertSame('1.0.0', $info->version);
        $this->assertSame('Title', $info->title);
        $this->assertSame('MIT', $info->license->name);
    }

    public function testServers()
    {
        $openapi    = $this->getReader();
        $servers    = $openapi->getServers();
        $this->assertCount(1, $servers);
        $this->assertInstanceOf('HKarlstrom\OpenApiReader\Objects\Server', $servers[0]);
        $server = $servers[0];
        $this->assertSame('http://testapi.com', $server->url);
    }

    public function testGetPathFromUri()
    {
        $openapi    = new OpenApiReader(__DIR__.'/testopenapi.json');
        $parameters = [];
        $this->assertNull($openapi->getPathFromUri('/foo', 'get'));
        $this->assertSame('/things', $openapi->getPathFromUri('/things', 'get'));
        $this->assertSame('/things', $openapi->getPathFromUri('/all/things', 'get'));
        $this->assertSame('/things/{thingId}', $openapi->getPathFromUri('/things/1', 'get', $parameters));
        $this->assertSame('1', $parameters['thingId']);
        $this->assertSame('/things/{thingId}', $openapi->getPathFromUri('/things/things', 'get', $parameters));
        $this->assertSame('things', $parameters['thingId']);
    }

    public function testGetOperationParameters()
    {
        $openapi    = $this->getReader();
        $parameters = $openapi->getOperationParameters('/things', 'get');
        $parameter  = $parameters[0];
        $this->assertInstanceOf('HKarlstrom\OpenApiReader\Objects\Parameter', $parameter);
        $this->assertSame('limit', $parameter->name);
        $this->assertSame('query', $parameter->in);
        $this->assertSame('How many items to return at one time (max 100)', $parameter->description);
        $this->assertFalse($parameter->required);
        $this->assertFalse($parameter->deprecated);
        $this->assertFalse($parameter->allowEmptyValue);
        $this->assertFalse($parameter->allowReserved);
        $this->assertSame('integer', $parameter->schema->type);
        $this->assertSame('int32', $parameter->schema->format);
    }

    public function testGetOperationResponseCodes()
    {
        $openapi    = $this->getReader();
        $codes      = $openapi->getOperationResponseCodes('/things', 'get');
        $this->assertSame([200, 'default'], $codes);
    }

    public function testGetOperationResponseHeaders()
    {
        $openapi    = $this->getReader();
        $response   = $openapi->getOperationResponse('/all/', 'get', 200);
        $headers    = $response->getHeaders();
        $this->assertInstanceOf('HKarlstrom\OpenApiReader\Objects\Header', $headers['x-next']);
        $this->assertInstanceOf('HKarlstrom\OpenApiReader\Objects\Header', $headers['x-response-id']);
        $this->assertFalse(isset($headers['x-not-defined']));
        $this->assertSame(['x-next', 'x-response-id'], array_keys($headers));
    }

    public function testReadReferencedValues()
    {
        $openapi    = $this->getReader('ref');
        $response   = $openapi->getOperationResponse('/api/v4/admin/loyalty/brands/{brand}/vip/rewards/analytics', 'get', 500);
        $this->assertArrayHasKey('X-Response-Id', $response->getHeaders());
    }

    abstract protected function getReader(string $filename = 'testopenapi') : OpenApiReader;
}
