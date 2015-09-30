<?php

namespace Tystr\RestOrm\Response;

use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class StandardResponseMapper implements ResponseMapperInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Maps a response body to an object
     *
     * @param ResponseInterface $response
     * @param string            $class
     * @param string            $format
     *
     * @return object
     */
    public function map(ResponseInterface $response, $class, $format)
    {
        return $this->serializer->deserialize((string) $response->getBody(), $class, $format);
    }
}
