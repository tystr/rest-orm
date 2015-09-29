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

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @param string $method
     * @param object $object
     *
     * @return Request
     */
    public function createRequest($method, $object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException();
        }

        $metadata = $this->metadataRegistry->getMetadataForClass(get_class($object));

        switch ($method) {
            case 'POST':
                $uri = $this->urlGenerator->getCreateUrl($metadata->getResource());
                $body = $this->serializer->serialize($object, $this->format);
                break;
            case 'PUT':
                $uri = $this->urlGenerator->getModifyUrl($metadata->getResource(), $metadata->getIdentifierValue($object));
                $body = $this->serializer->serialize($object, $this->format);
                break;
            case 'GET':
                $uri = $this->urlGenerator->getModifyUrl($metadata->getResource(), $metadata->getIdentifierValue($object));
                $body = null;
                break;
            case 'PATCH':
                throw new \RuntimeException('PATCH not yet implemented.');
            default:
                throw new InvalidArgumentException(sprintf('Unsupported HTTP method "%s".', $method));
        }

        $headers = ['Content-Type' => $this->getContentTypeHeader()];

        return new Request($method, $uri, $headers, $body);
    }

    /**
     * @return string
     */
    public function getContentTypeHeader()
    {
        switch ($this->format) {
            case 'json':
                return 'application/json';
            case 'xml':
                return 'application/xml';
            default:
                throw new InvalidArgumentException(sprintf('Unsupported format "%s".', $this->format));
        }
    }
}