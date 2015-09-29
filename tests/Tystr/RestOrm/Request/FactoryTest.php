<?php

namespace Tystr\RestOrm\Request;

use Tystr\RestOrm\Model\Blog;
use Tystr\RestOrm\Request\Factory as RequestFactory;
use Tystr\RestOrm\Metadata\Registry;
use Tystr\RestOrm\Metadata\Factory as MetadataFactory;
use Tystr\RestOrm\UrlGenerator\StandardUrlGenerator;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $metadataFactory = new MetadataFactory();
        $registry = new Registry($metadataFactory);
        $serializer = $this->getMockBuilder('JMS\Serializer\SerializerInterface')->getMock();
        $urlGenerator = new StandardUrlGenerator();
        $factory = new RequestFactory($registry, $serializer, $urlGenerator, 'json');

        $request = $factory->createRequest('POST', new Blog());

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/blogs', $request->getUri());

    }
}