<?php

namespace Tystr\RestOrm\Response;

use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class HalResponseMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMapWithHalCollection()
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $response = new Response(200, $headers, $hal = $this->getHalCollection());

        $mapper = new HalResponseMapper(SerializerBuilder::create()->build());

        $blogs = $mapper->map($response, 'Tystr\RestOrm\Model\BlogHal', 'json');
        $this->assertEquals(1, $blogs[0]->id);
        $this->assertEquals('Hello, Hal!', $blogs[0]->body);
        $this->assertEquals(2, $blogs[1]->id);
        $this->assertEquals('Goodbye, Hal!', $blogs[1]->body);
    }

    public function testMap()
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $response = new Response(200, $headers, $hal = $this->getHalResource());
        $mapper = new HalResponseMapper(SerializerBuilder::create()->build());

        $blog = $mapper->map($response, 'Tystr\RestOrm\Model\BlogHal', 'json');
        $this->assertEquals(1, $blog->id);
        $this->assertEquals('Hello, Hal!', $blog->body);
    }

    protected function getHalCollection()
    {
        return <<<JSON
{
    "_links": {
        "self": {
            "href": "/api/blogs"
        }
    },
    "_embedded": {
        "blasdfafdog": [
            {
                "id": 1,
                "body": "Hello, Hal!"
            },
            {
                "id": 2,
                "body": "Goodbye, Hal!"
            }
        ]
    }
}
JSON;
    }

    protected function getHalResource()
    {
        return <<<JSON
{
    "_links": {
        "self": {
            "href": "/blogs/1"
        }
    },
    "id": 1,
    "body": "Hello, Hal!"
}
JSON;
    }
}
