<?php

namespace Tystr\RestOrm\Response;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Nocarrier\Hal;
use Psr\Http\Message\ResponseInterface;
use Tystr\RestOrm\Exception\InvalidArgumentException;
use Tystr\RestOrm\Metadata\Registry;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class HalResponseMapper implements ResponseMapperInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var Registry
     */
    private $metadataRegistry;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(Registry $metadataRegistry, SerializerInterface $serializer = null)
    {
        $this->metadataRegistry = $metadataRegistry;
        $this->serializer = $serializer ?: SerializerBuilder::create()->build();
    }

    /**
     * Maps a response body to an object.
     *
     * @param ResponseInterface $response
     * @param string            $class
     * @param string            $format
     *
     * @return object
     */
    public function map(ResponseInterface $response, $class, $format)
    {
        if ('json' === $format) {
            $hal = Hal::fromJson((string) $response->getBody(), 10);
        } elseif ('xml' === $format) {
            $hal = Hal::fromXml((string) $response->getBody(), 10);
        } else {
            throw new InvalidArgumentException(sprintf('Unsupported format "%s".', $format));
        }

        return $this->serializer->fromArray($this->getDataFromHal($hal, $class), $class);
    }

    /**
     * @param Hal    $hal
     * @param string $class
     *
     * @return array
     */
    protected function getDataFromHal(Hal $hal, $class)
    {
        $class = preg_match('/array<([^>]*)>/', $class, $matches) ? $matches[1] : $class;
        $metadata = $this->metadataRegistry->getMetadataForClass($class);

        $data = $this->flattenHal($hal);

        reset($data);
        if (count($data) === 1 && key($data) === $metadata->getEmbeddedRel()) {
            $data = $data[key($data)];
        }

        return $data;
    }

    /**
     * @param Hal  $hal
     * @param bool $isRoot
     *
     * @return array
     */
    protected function flattenHal(Hal $hal, $isRoot = true)
    {
        $data = $hal->getData();
        foreach ($hal->getResources() as $name => $items) {
            $data[$name] = [];
            foreach ($items as $item) {
                $data[$name][] = $this->flattenHal($item);
            }
        }

        return $data;
    }
}
