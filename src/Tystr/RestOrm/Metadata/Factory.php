<?php

namespace Tystr\RestOrm\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class Factory
{
    /**
     * @param string $class
     *
     * @return Metadata
     */
    public function create($class)
    {
        $reader = new AnnotationReader();
        $resource = $reader->getClassAnnotation(new ReflectionClass($class), Resource::class);

        $metadata = new Metadata($class);
        $metadata->setResource($resource->value);

        return $metadata;
    }
}