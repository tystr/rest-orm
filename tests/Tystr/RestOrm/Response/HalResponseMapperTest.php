<?php

namespace Tystr\RestOrm\Response;

use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;
use Tystr\RestOrm\Metadata\Registry;
use Tystr\RestOrm\Model\Comment;

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

        $registry = new Registry();
        $mapper = new HalResponseMapper($registry, SerializerBuilder::create()->build());

        $blogs = $mapper->map($response, 'array<Tystr\RestOrm\Model\BlogHal>', 'json');
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
        $registry = new Registry();
        $mapper = new HalResponseMapper($registry, SerializerBuilder::create()->build());

        $blog = $mapper->map($response, 'Tystr\RestOrm\Model\BlogHal', 'json');
        $this->assertEquals(1, $blog->id);
        $this->assertEquals('Hello, Hal!', $blog->body);
    }

    /**
     * @dataProvider getHalResourceWithEmbedded
     */
    public function testMapWithEmbedded($hal, $expectedComments)
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $response = new Response(200, $headers, $hal);
        $registry = new Registry();
        $mapper = new HalResponseMapper($registry, SerializerBuilder::create()->build());

        $blog = $mapper->map($response, 'Tystr\RestOrm\Model\BlogHal', 'json');
        $this->assertEquals(1, $blog->id);
        $this->assertEquals('Hello, Hal!', $blog->body);
        $this->assertCount(1, $blog->comments);
        foreach ($blog->comments as $comment) {
            $this->assertEquals($expectedComments[0], $comment);
        }
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
        "blogs": [
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

    public function getHalResourceWithEmbedded()
    {
        $json = <<<JSON
{
    "_links": {
        "self": {
            "href": "/blogs/1"
        }
    },
    "id": 1,
    "body": "Hello, Hal!",
    "_embedded": {
        "comments": [
            {
                "id": 14,
                "body": "This is a comment"
            }
        ]
    }
}
JSON;
        $expected = [
            new Comment(14, 'This is a comment')
        ];

        return [[$json, $expected]];
    }
}
