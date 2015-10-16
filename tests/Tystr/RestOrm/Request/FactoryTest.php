<?php

namespace Tystr\RestOrm\Request;

use JMS\Serializer\SerializerBuilder;
use Tystr\RestOrm\Model\Blog;
use Tystr\RestOrm\Model\BlogGroup;
use Tystr\RestOrm\Model\Post;
use Tystr\RestOrm\Request\Factory as RequestFactory;
use Tystr\RestOrm\Metadata\Registry;
use Tystr\RestOrm\Metadata\Factory as MetadataFactory;

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
        $this->urlGenerator = $this->getMockBuilder('Tystr\RestOrm\UrlGenerator\UrlGeneratorInterface')->getMock();
        $this->factory = new RequestFactory($this->urlGenerator, 'json', $this->registry, $this->serializer);
    }

    /**
     * @dataProvider getFormatAndBodyWithoutId
     */
    public function testCreateSaveRequestForNewEntity($format, $expectedBody, $parameters, $requirements, $expectedQueryString)
    {
        $this->factory->setFormat($format);
        $blog = new Blog();
        $blog->body = 'Hello World!';
        $this->urlGenerator->expects($this->once())
            ->method('getCreateUrl')
            ->with('blogs', $parameters, $requirements)
            ->will($this->returnValue('/blogs'.$expectedQueryString));

        $request = $this->factory->createSaveRequest($blog, $parameters);

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs'.$expectedQueryString, $request->getUri());
        $this->assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBodyWithoutId
     */
    public function testCreateSaveRequestForEntityWithSerializationGroups($format, $expectedBody, $parameters, $requirements, $expectedQueryString)
    {
        $this->factory->setFormat($format);
        $blog = new BlogGroup();
        $blog->id = 123; // This property has the @Groups("READ") annotation and should be excluded from the request body
        $blog->body = 'Hello World!';

        $this->urlGenerator->expects($this->once())
            ->method('getModifyUrl')
            ->with('blogs', 123, $parameters, $requirements)
            ->will($this->returnValue('/blogs'.$expectedQueryString));

        $request = $this->factory->createSaveRequest($blog, $parameters);

        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs'.$expectedQueryString, $request->getUri());
        $this->assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBodyWithoutId
     */
    public function testCreateSaveRequestForEntityWithUrlRequirements($format, $expectedBody, $parameters, $requirements, $expectedQueryString)
    {
        $this->factory->setFormat($format);
        $post = new Post();
        $post->body = 'Hello World!';

        $requirements['blogId'] = 321;

        $this->urlGenerator->expects($this->once())
            ->method('getCreateUrl')
            ->with('blogs/{{blogId}}/posts', $parameters, $requirements)
            ->will($this->returnValue('/blogs/321/posts'.$expectedQueryString));

        $request = $this->factory->createSaveRequest($post, $parameters, $requirements);

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs/321/posts'.$expectedQueryString, $request->getUri());
        $this->assertEquals($expectedBody, (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateFindOneRequest($format, $expectedBody, $parameters, $requirements, $expectedQueryString)
    {
        $this->factory->setFormat($format);
        $this->urlGenerator->expects($this->once())
            ->method('getFindOneUrl')
            ->with('blogs', 42, $parameters, $requirements)
            ->will($this->returnValue('/blogs/42'.$expectedQueryString));

        $request = $this->factory->createFindOneRequest(Blog::class, 42, $parameters);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs/42'.$expectedQueryString, $request->getUri());
        $this->assertEquals('', (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateFindAllRequest($format, $expectedBody, $parameters, $requirements, $expectedQueryString)
    {
        $this->factory->setFormat($format);
        $this->urlGenerator->expects($this->once())
            ->method('getFindAllUrl')
            ->with('blogs', $parameters, $requirements)
            ->will($this->returnValue('/blogs'.$expectedQueryString));

        $request = $this->factory->createFindAllRequest(Blog::class, $parameters);

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs'.$expectedQueryString, $request->getUri());
        $this->assertEquals('', (string) $request->getBody());
    }

    /**
     * @dataProvider getFormatAndBody
     */
    public function testCreateDeleteRequest($format, $expectedBody, $parameters, $requirements, $expectedQueryString)
    {
        $blog = new Blog();
        $blog->id = 42;
        $this->factory->setFormat($format);
        $this->urlGenerator->expects($this->once())
            ->method('getRemoveUrl')
            ->with('blogs', 42, $parameters, $requirements)
            ->will($this->returnValue('/blogs/42'.$expectedQueryString));

        $request = $this->factory->createDeleteRequest($blog, $parameters);

        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('application/'.$format, $request->getHeaderLine('Content-Type'));
        $this->assertEquals('/blogs/42'.$expectedQueryString, $request->getUri());
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
            ['json', '{"id":42,"body":"Hello World!"}', ['limit' => 10], [], '?limit=10'],
            ['json', '{"id":42,"body":"Hello World!"}', ['limit' => 10], [], '?limit=10'],
            ['xml', $expectedXml, [], [], ''],
            ['xml', $expectedXml, [], [], ''],
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
            ['json', '{"body":"Hello World!"}', ['limit' => 10], [], '?limit=10'],
            ['json', '{"body":"Hello World!"}', [], [], ''],
            ['xml', $expectedXml, ['limit' => 10], [], '?limit=10'],
            ['xml', $expectedXml, [], [], ''],
        ];
    }
}
