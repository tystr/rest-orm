<?php

namespace Tystr\RestOrm\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;
use Tystr\RestOrm\Metadata\Metadata;
use Tystr\RestOrm\Model\Blog;
use Tystr\RestOrm\Response\StandardResponseMapper;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $client;
    protected $requestFactory;
    protected $responseMapper;
    protected $metadataRegistry;
    protected $factory;
    protected $customFactory;
    protected $customRepoDependency;

    public function setUp()
    {
        $this->client = new Client();
        $this->requestFactory = $this->getMockBuilder('Tystr\RestOrm\Request\Factory')->disableOriginalConstructor()->getMock();
        $this->responseMapper = $this->getMockBuilder('Tystr\RestOrm\Response\ResponseMapperInterface')->getMock();
        $this->metadataRegistry = $this->getMockBuilder('Tystr\RestOrm\Metadata\Registry')->getMock();
        $this->customRepoDependency = 'custom-repo-dep';

        $this->factory = new RepositoryFactory(
            $this->client,
            $this->requestFactory,
            $this->responseMapper,
            $this->metadataRegistry
        );

        $this->customFactory = new ChildRepositoryFactory(
            $this->client,
            $this->requestFactory,
            $this->responseMapper,
            $this->metadataRegistry,
            $this->customRepoDependency
        );
    }

    public function testGetRepository()
    {
        $metadata = new Metadata(new \ReflectionClass('Tystr\RestOrm\Model\Blog'));
        $metadata->setRepositoryClass('Tystr\RestOrm\Repository\Repository');
        $this->metadataRegistry->expects($this->once())
            ->method('getMetadataForClass')
            ->with('Tystr\RestOrm\Model\Blog')
            ->willReturn($metadata);

        $repository = $this->factory->getRepository('Tystr\RestOrm\Model\Blog');
        $this->assertInstanceOf('Tystr\RestOrm\Repository\Repository', $repository);
        $this->assertSame($repository, $this->factory->getRepository('Tystr\RestOrm\Model\Blog'));
    }

    public function testChildGetRepository()
    {
        $metadata = new Metadata(new \ReflectionClass('Tystr\RestOrm\Model\Blog'));
        $metadata->setRepositoryClass('Tystr\RestOrm\Repository\ChildRepository');
        $this->metadataRegistry->expects($this->once())
            ->method('getMetadataForClass')
            ->with('Tystr\RestOrm\Model\Blog')
            ->willReturn($metadata);

        // same tests as self::testGetRepository()
        $repository = $this->customFactory->getRepository('Tystr\RestOrm\Model\Blog');
        $this->assertInstanceOf('Tystr\RestOrm\Repository\ChildRepository', $repository);

        // additional tests on custom repo
        $this->assertEquals('Tystr\RestOrm\Model\Blog', $repository->getModelClass());
        $this->assertSame($this->customRepoDependency, $repository->getCustomDependency());
    }

    /**
     * @expectedException Tystr\RestOrm\Exception\InvalidArgumentException
     */
    public function testGetRepositoryThrowsExceptionIfRepositoryDoesNotImplementRepositoryInterface()
    {
        $metadata = new Metadata(new \ReflectionClass('stdClass'));
        $metadata->setRepositoryClass('stdClass');
        $this->metadataRegistry->expects($this->once())
            ->method('getMetadataForClass')
            ->with('stdClass')
            ->willReturn($metadata);

        $repository = $this->factory->getRepository('stdClass');
    }
}