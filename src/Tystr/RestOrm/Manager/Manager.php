<?php

namespace Tystr\RestOrm\Manager;

use GuzzleHttp\ClientInterface;
use Tystr\RestOrm\Request\Factory;
use Tystr\RestOrm\Exception\InvalidArgumentException;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class Manager
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
     * @param ClientInterface $client
     * @param Factory         $requestFactory
     */
    public function __construct(ClientInterface $client, Factory $requestFactory) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param object $object
     *
     * @return object
     */
    public function save($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException();
        }

        $request = $this->requestFactory->createRequest('POST', $object);
        $response = $this->client->send($request);

        // @todo handle response

        return $object;
    }

    /**
     * @param $id
     */
    public function findOneById($id)
    {

    }
}
