<?php

namespace Tystr\RestOrm\Metadata;

use PHPUnit\Framework\TestCase;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class FactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new Factory();
        $metadata = $factory->create('Tystr\RestOrm\Model\Blog');

        $this->assertEquals('Tystr\RestOrm\Model\Blog', $metadata->getClass());
        $this->assertEquals('Tystr\RestOrm\Repository\BlogRepository', $metadata->getRepositoryClass());
        $this->assertEquals('blogs', $metadata->getResource());
        $this->assertEquals('id', $metadata->getIdentifier());
    }

    /**
     * @expectedException Tystr\RestOrm\Exception\MissingIdentifierMappingException
     */
    public function testCreateThrowsExceptionWhenIdentifierMappingIsMissing()
    {
        $factory = new Factory();
        $factory->create('Tystr\RestOrm\Model\BlogMissingIdentifier');
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
