<?php

namespace Tystr\RestOrm\Repository;

use GuzzleHttp\ClientInterface;
use Tystr\RestOrm\Request\Factory;
use Tystr\RestOrm\Exception\InvalidArgumentException;
use Tystr\RestOrm\Response\ResponseMapperInterface;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class Repository implements RepositoryInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Factory
     */
    private $requestFactory;

    /**
     * @var ResponseMapperInterface
     */
    private $responseMapper;

    /**
     * @var string
     */
    private $class;

    /**
     * @param ClientInterface         $client
     * @param Factory                 $requestFactory
     * @param ResponseMapperInterface $responseMapper
     * @param string                  $class
     */
    public function __construct(
        ClientInterface $client,
        Factory $requestFactory,
        ResponseMapperInterface $responseMapper,
        $class
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->responseMapper = $responseMapper;
        $this->class = $class;
    }

    /**
     * @param object $object
     * @param bool   $mapResponse
     * @param array  $parameters
     *
     * @return object
     */
    public function save($object, $mapResponse = false, array $parameters = [])
    {
        if (!$object instanceof $this->class) {
            throw new InvalidArgumentException();
        }

        $request = $this->requestFactory->createSaveRequest($object, $parameters);
        $response = $this->client->send($request);

        if (true === $mapResponse) {
            return $this->responseMapper->map($response, get_class($object), 'json');
        }

        return $object;
    }

    /**
     * @param $id
     * @param array $parameters
     *
     * @return object
     */
    public function findOneById($id, array $parameters = [])
    {
        $request = $this->requestFactory->createFindOneRequest($this->class, $id, $parameters);
        $response = $this->client->send($request);

        return $this->responseMapper->map($response, $this->class, 'json');
    }

    /**
     * @param array $parameters
     *
     * @return object
     */
    public function findAll(array $parameters = [])
    {
        $request = $this->requestFactory->createFindAllRequest($this->class, $parameters);
        $response = $this->client->send($request);

        return $this->responseMapper->map($response, sprintf('array<%s>', $this->class), 'json');
    }

    /**
     * @param object $object
     * @param array  $parameters
     */
    public function remove($object, array $parameters = [])
    {
        if (!$object instanceof $this->class) {
            throw new InvalidArgumentException();
        }

        $request = $this->requestFactory->createDeleteRequest($object, $parameters);
        $response = $this->client->send($request);

        return true;
    }
}
