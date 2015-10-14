<?php

namespace Tystr\RestOrm\Metadata;

use Tystr\RestOrm\Model\Blog;
use Tystr\RestOrm\Model\BlogPrivateProperties;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIdentifierValue()
    {
        $metadata = new Metadata(new \ReflectionClass('Tystr\RestOrm\Model\Blog'));
        $metadata->setIdentifier('id');

        $blog = new Blog();
        $blog->id = 42;
        $this->assertEquals(42, $metadata->getIdentifierValue($blog));
    }

    public function testGetIdentifierValueWithPrivateProperties()
    {
        $metadata = new Metadata(new \ReflectionClass('Tystr\RestOrm\Model\BlogPrivateProperties'));
        $metadata->setIdentifier('id');

        $blog = new BlogPrivateProperties();
        $blog->setId(42);
        $this->assertEquals(42, $metadata->getIdentifierValue($blog));
    }

    /**
     * @expectedException Tystr\RestOrm\Exception\InvalidArgumentException
     */
    public function testGetIdentifierValueThrowsException()
    {
        $metadata = new Metadata(new \ReflectionClass('Tystr\RestOrm\Model\Blog'));
        $metadata->setIdentifier('id');

        $this->assertEquals(42, $metadata->getIdentifierValue(new \stdClass()));
    }
}
