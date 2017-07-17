<?php

namespace Tystr\RestOrm\Metadata;

use PHPUnit\Framework\TestCase;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class RegistryTest extends TestCase
{
    protected $class;
    protected $factory;
    protected $registry;

    public function setUp()
    {
        $this->class = 'Tystr\RestOrm\Model\Blog';
        $this->factory = $this->getMockBuilder('Tystr\RestOrm\Metadata\Factory')->getMock();
        $this->registry = new Registry($this->factory);
    }

    public function testGetMetadataForClass()
    {
        $expected = new Metadata(new \ReflectionClass($this->class));
        $this->factory->expects($this->once())
            ->method('create')
            ->with($this->class)
            ->willReturn($expected);

        $metadata = $this->registry->getMetadataForClass($this->class);

        $this->assertSame($expected, $metadata);
    }

    public function testGetMetadataForClassWithSubsequentCalls()
    {
        $expected = new Metadata(new \ReflectionClass($this->class));
        $this->factory->expects($this->once())
            ->method('create')
            ->with($this->class)
            ->willReturn($expected);

        $this->registry->getMetadataForClass($this->class);
        $metadata = $this->registry->getMetadataForClass($this->class);
        $this->assertSame($expected, $metadata);
    }

    public function testAddMetadata()
    {
        $this->factory->expects($this->never())
            ->method('create');

        $expected = new Metadata(new \ReflectionClass($this->class));
        $this->registry->addMetadata($expected);
        $metadata = $this->registry->getMetadataForClass($this->class);
        $this->assertSame($expected, $metadata);
    }
}
