<?php

namespace Tystr\RestOrm\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Tystr\RestOrm\Annotation\Resource;
use Tystr\RestOrm\Metadata\Metadata;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new Factory();
        $metadata = $factory->create('Tystr\RestOrm\Model\Blog');

        $this->assertEquals('Tystr\RestOrm\Model\Blog', $metadata->getClass());
        $this->assertEquals('blogs', $metadata->getResource());
        $this->assertEquals('id', $metadata->getIdentifier());
    }

    /**
     * @expectedException Tystr\RestOrm\Exception\MissingIdentifierMappingException
     */
    public function testCreateThrowsExceptionWhenIdentifierMappingIsMissing()
    {
        $factory = new Factory();
        $factory->create('stdClass');
    }

    /**
     * @expectedException Tystr\RestOrm\Exception\InvalidIdentifierMappingException
     */
    public function testCreateThrowsExceptionWhenMultiplePropertiesHaveAnIdentifierMapping()
    {
        $factory = new Factory();
        $factory->create('Tystr\RestOrm\Model\BlogInvalidIdentifierMapping');
    }
}
