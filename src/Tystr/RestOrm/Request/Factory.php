<?php

namespace Tystr\RestOrm\Request;

use JMS\Serializer\SerializerInterface;
use Tystr\RestOrm\Metadata\Registry;
use GuzzleHttp\Psr7\Request;
use Tystr\RestOrm\Exception\InvalidArgumentException;
use Tystr\RestOrm\UrlGenerator\UrlGeneratorInterface;

class Factory
{
    /**
     * @var Registry
     */
    private $metadataRegistry;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var string
     */
    private $format;

    /**
     * @param Registry              $metadataRegistry
     * @param SerializerInterface   $serializer
     * @param UrlGeneratorInterface $urlGenerator
     *
     * @param string   $format
     */
    public function __construct(
        Registry $metadataRegistry,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator,
        $format
    ) {
        $this->metadataRegistry = $metadataRegistry;
        $this->urlGenerator = $urlGenerator;
        $this->serializer = $serializer;
        $this->format = $format;
    }

    public function createRequest($method, $object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException();
        }

        $metadata = $this->metadataRegistry->getMetadataForClass(get_class($object));

        return new Request(
            $method,
            $this->urlGenerator->getCreateUrl($metadata->getResource()),
            [],
            $this->serializer->serialize($object, $this->format)
        );
    }
}