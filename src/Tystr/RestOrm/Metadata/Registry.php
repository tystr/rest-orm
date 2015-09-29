<?php

namespace Tystr\RestOrm\Metadata;

use Tystr\RestOrm\Metadata\Factory;
use Tystr\RestOrm\Metadata\Metadata;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class Registry
{
    /**
     * @var array
     */
    private $metadata = [];

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Metadata $metadata
     */
    public function addMetadata(Metadata $metadata)
    {
        $this->metadata[$metadata->getClass()] = $metadata;
    }

    /**
     * @param string $class
     *
     * @return Metadata
     */
    public function getMetadataForClass($class)
    {
        if (!isset($this->metadata[$class])) {
            $this->metadata[$class] = $this->factory->create($class);
        }

        return $this->metadata[$class];
    }
}
