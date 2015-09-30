<?php

namespace Tystr\RestOrm\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use Tystr\RestOrm\Annotation\Resource;
use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Exception\InvalidIdentifierMappingException;
use Tystr\RestOrm\Exception\MissingIdentifierMappingException;

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
        $reflClass = new ReflectionClass($class);
        $resource = $reader->getClassAnnotation($reflClass, Resource::class);

        $metadata = new Metadata($reflClass);
        $metadata->setResource($resource->value);
        $identifier = $this->getIdentifier($reflClass, $reader);
        if (null === $identifier) {
            throw new MissingIdentifierMappingException('You must specify an identifier mapping.');
        }

        $metadata->setIdentifier($identifier);

        return $metadata;
    }

    /**
     * @param ReflectionClass  $reflClass
     * @param AnnotationReader $reader
     *
     * @return string
     */
    private function getIdentifier(ReflectionClass $reflClass, AnnotationReader $reader)
    {
        $identifier = null;
        $count = 0;
        foreach ($reflClass->getProperties() as $reflProperty) {
            if (null !== $reader->getPropertyAnnotation($reflProperty, Id::class)) {
                ++$count;
                $identifier = $reflProperty->getName();
            }
        }
        if ($count > 1) {
            throw new InvalidIdentifierMappingException(
                'You cannot specify an identifier mapping on more than one property.'
            );
        }

        return $identifier;
    }
}
