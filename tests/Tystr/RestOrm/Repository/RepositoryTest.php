<?php

namespace Tystr\RestOrm\Repository;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;
use Tystr\RestOrm\Model\Blog;
use Tystr\RestOrm\Response\StandardResponseMapper;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class RepositoryTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;
    protected $manager;
    protected $mock;
    protected $transactions = [];
    protected $history;
    protected $stack;
    protected $factory;

    public function setUp()
    {
        $this->mock = new MockHandler();
        $this->history = Middleware::history($this->transactions);
        $this->stack = HandlerStack::create($this->mock);
        $this->stack->push($this->history);
        $clientHeaders = [
            'Authorization' => 'Token abcdefg',
            'User-Agent' => 'RestOrm',
        ];
        $this->client = new Client(['handler' => $this->stack, 'headers' => $clientHeaders]);
        $this->factory = $this->getMockBuilder('Tystr\RestOrm\Request\Factory')->disableOriginalConstructor()->getMock();

        $serializer = SerializerBuilder::create()->build();
        $this->manager = new Repository(
            $this->client,
            $this->factory,
            new StandardResponseMapper($serializer),
            'Tystr\RestOrm\Model\Blog'
        );
    }

    public function testSave()
    {
        // Add mock response. This should be handled by the Response mapper to return an object with the response data
        $response = new Response(
            200,
            ['Content-Type' => 'json'],
            '{"id": 1, "body": "Hello World."}'
        );
        $this->mock->append($response);

        $requestBody = '{"body": "Hello World."}';
        $requirements = ['categoryId' => 12345];
        $parameters = ['query_param_1' => 'hello'];
        $mockRequest = new Request('POST', '/blogs', ['Content-Type' => 'application/json'], $requestBody);

        $blog = new Blog();
        $blog->body = 'Hello World.';

        $this->factory->expects($this->once())
            ->method('createSaveRequest')
            ->with($blog, $parameters, $requirements)
            ->willReturn($mockRequest);

        $savedBlog = $this->manager->save($blog, true, $parameters, $requirements);

        $this->assertEquals(1, $savedBlog->id);
        $this->assertEquals('Hello World.', $savedBlog->body);

        $expectedHeaders = [
            'Content-Length' => [24],
            'Content-Type' => ['application/json'],
            'Authorization' => ['Token abcdefg'],
            'User-Agent' => ['RestOrm'],
        ];

        foreach ($this->transactions as $transaction) {
            $this->assertEquals($requestBody, (string) $transaction['request']->getBody());
            $this->assertEquals('/blogs', $transaction['request']->getUri());
            $this->assertEquals($expectedHeaders, $transaction['request']->getHeaders());

            $this->assertSame($response, $transaction['response']);
        }
    }

    public function testFindOneById()
    {
        // Add mock response. This should be handled by the Response mapper to return an object with the response data
        $response = new Response(
            200,
            ['Content-Type' => 'json'],
            '{"id": 1, "body": "Hello World."}'
        );
        $this->mock->append($response);

        $requirements = ['categoryId' => 12345];
        $parameters = ['query_param_1' => 'hello'];
        $mockRequest = new Request('GET', '/blogs/1', ['Content-Type' => 'application/json']);

        $this->factory->expects($this->once())
            ->method('createFindOneRequest')
            ->with(Blog::class, 1, $parameters, $requirements)
            ->willReturn($mockRequest);

        $blog = $this->manager->findOneById(1, $parameters, $requirements);

        $this->assertInstanceOf(Blog::class, $blog);
        $this->assertEquals(1, $blog->id);
        $this->assertEquals('Hello World.', $blog->body);

        $expectedHeaders = [
            'Content-Type' => ['application/json'],
            'Authorization' => ['Token abcdefg'],
            'User-Agent' => ['RestOrm'],
        ];

        foreach ($this->transactions as $transaction) {
            $this->assertEquals('', (string) $transaction['request']->getBody());
            $this->assertEquals('/blogs/1', $transaction['request']->getUri());
            $this->assertEquals($expectedHeaders, $transaction['request']->getHeaders());

            $this->assertSame($response, $transaction['response']);
        }
    }

    public function testFindAll()
    {
        // Add mock response. This should be handled by the Response mapper to return an object with the response data
        $response = new Response(
            200,
            ['Content-Type' => 'json'],
            '[{"id": 1, "body": "Hello World."},{"id": 2, "body": "Goodbye World."}]'
        );
        $this->mock->append($response);

        $requirements = ['categoryId' => 12345];
        $parameters = ['query_param_1' => 'hello'];
        $mockRequest = new Request('GET', '/blogs', ['Content-Type' => 'application/json']);

        $this->factory->expects($this->once())
            ->method('createFindAllRequest')
            ->with(Blog::class, $parameters, $requirements)
            ->willReturn($mockRequest);

        $blogs = $this->manager->findAll($parameters, $requirements);

        $this->assertTrue(is_array($blogs));
        $this->assertCount(2, $blogs);

        $this->assertInstanceOf(Blog::class, $blogs[0]);
        $this->assertEquals(1, $blogs[0]->id);
        $this->assertEquals('Hello World.', $blogs[0]->body);

        $this->assertInstanceOf(Blog::class, $blogs[1]);
        $this->assertEquals(2, $blogs[1]->id);
        $this->assertEquals('Goodbye World.', $blogs[1]->body);

        $expectedHeaders = [
            'Content-Type' => ['application/json'],
            'Authorization' => ['Token abcdefg'],
            'User-Agent' => ['RestOrm'],
        ];

        foreach ($this->transactions as $transaction) {
            $this->assertEquals('', (string) $transaction['request']->getBody());
            $this->assertEquals('/blogs', $transaction['request']->getUri());
            $this->assertEquals($expectedHeaders, $transaction['request']->getHeaders());

            $this->assertSame($response, $transaction['response']);
        }
    }

    public function testRemove()
    {
        // Add mock response. This should be handled by the Response mapper to return an object with the response data
        $response = new Response(
            200,
            ['Content-Type' => 'json'],
            '{"id": 1, "body": "Hello World."}'
        );
        $this->mock->append($response);

        $requirements = ['categoryId' => 12345];
        $parameters = ['query_param_1' => 'hello'];
        $mockRequest = new Request('GET', '/blogs/1', ['Content-Type' => 'application/json']);

        $blog = new Blog();
        $blog->id = 1;
        $this->factory->expects($this->once())
            ->method('createDeleteRequest')
            ->with($blog, $parameters, $requirements)
            ->willReturn($mockRequest);

        $success = $this->manager->remove($blog, $parameters, $requirements);
        $this->assertTrue($success);

        $expectedHeaders = [
            'Content-Type' => ['application/json'],
            'Authorization' => ['Token abcdefg'],
            'User-Agent' => ['RestOrm'],
        ];

        foreach ($this->transactions as $transaction) {
            $this->assertEquals('', (string) $transaction['request']->getBody());
            $this->assertEquals('/blogs/1', $transaction['request']->getUri());
            $this->assertEquals($expectedHeaders, $transaction['request']->getHeaders());

            $this->assertSame($response, $transaction['response']);
        }
    }
}
