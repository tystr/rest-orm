<?php

namespace Tystr\RestOrm\Request;

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
     * @dataProvider getFormatAndBodyWithoutId
     */
    public function testCreateSaveRequestForNewEntity($format, $expectedBody)
    {
        $this->factory->setFormat($format);
        $blog = new Blog();
        $blog->body = 'Hello World!';

        $request = $this->factory->createSaveRequest($blog);

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs', $request->getUri());
        $this->assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateFindOneRequest($format)
    {
        $this->factory->setFormat($format);
        $request = $this->factory->createFindOneRequest(Blog::class, 42);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs/42', $request->getUri());
        $this->assertEquals('', (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateFindAllRequest($format)
    {
        $this->factory->setFormat($format);
        $request = $this->factory->createFindAllRequest(Blog::class);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs', $request->getUri());
        $this->assertEquals('', (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateDeleteRequest($format)
    {
        $blog = new Blog();
        $blog->id = 42;
        $this->factory->setFormat($format);
        $request = $this->factory->createDeleteRequest($blog);

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs/42', $request->getUri());
        $this->assertEquals('', (string) $request->getBody());
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

    public function getFormatAndBodyWithoutId()
    {
        $expectedXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <body><![CDATA[Hello World!]]></body>
</result>

XML;

        return [
            ['json', '{"body":"Hello World!"}'],
            ['xml', $expectedXml],
        ];
    }
}
