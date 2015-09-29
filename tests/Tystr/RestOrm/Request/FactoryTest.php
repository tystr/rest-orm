<?php

namespace Tystr\RestOrm\Request;

use DoctrineTest\InstantiatorTestAsset\XMLReaderAsset;
use JMS\Serializer\SerializerBuilder;
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
    protected $metadataFactory;
    protected $registry;
    protected $serializer;
    protected $urlGenerator;
    /**
     * @var Factory
     */
    protected $factory;

    public function setUp()
    {
        $this->metadataFactory = new MetadataFactory();
        $this->registry = new Registry($this->metadataFactory);
        $this->serializer = SerializerBuilder::create()->build();
        $this->urlGenerator = new StandardUrlGenerator();
        $this->factory = new RequestFactory($this->registry, $this->serializer, $this->urlGenerator, 'json');

    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateForPOST($format, $expectedBody)
    {
        $this->factory->setFormat($format);
        $blog = new Blog();
        $blog->id = 42;
        $blog->body = 'Hello World!';

        $request = $this->factory->createRequest('POST', $blog);

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs', $request->getUri());
        $this->assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateForPUT($format, $expectedBody)
    {
        $this->factory->setFormat($format);
        $blog = new Blog();
        $blog->id = 42;
        $blog->body = 'Hello World!';

        $request = $this->factory->createRequest('PUT', $blog);

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs/42', $request->getUri());
        $this->assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateForGET($format)
    {
        $blog = new Blog();
        $blog->id = 42;
        $this->factory->setFormat($format);
        $request = $this->factory->createRequest('GET', $blog);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs/42', $request->getUri());
        $this->assertEquals('', (string) $request->getBody());
    }

    /**
     * @expectedException Tystr\RestOrm\Exception\InvalidArgumentException
     */
    public function testCreateThrowsExceptionWithInvalidHttpMethod()
    {
        $blog = new Blog();
        $blog->id = 42;
        $this->factory->createRequest('HEAD', $blog);
    }

    public function getFormatAndBody()
    {
        $expectedXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <id>42</id>
  <body><![CDATA[Hello World!]]></body>
</result>

XML;

        return [
            ['json', '{"id":42,"body":"Hello World!"}'],
            ['xml', $expectedXml],
        ];
    }
}