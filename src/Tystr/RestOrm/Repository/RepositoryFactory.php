<?php

namespace Tystr\RestOrm\Repository;

use GuzzleHttp\ClientInterface;
use Tystr\RestOrm\Exception\InvalidArgumentException;
use Tystr\RestOrm\Metadata\Registry;
use Tystr\RestOrm\Request\Factory as RequestFactory;
use Tystr\RestOrm\Response\ResponseMapperInterface;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class RepositoryFactory implements RepositoryFactoryInterface
{
    /**
     * @var RepositoryInterface[]
     */
    private $repositories = [];

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ResponseMapperInterface
     */
    private $responseMapper;

    /**
     * @var Registry
     */
    private $metadataRegistry;

    /**
     * @param ClientInterface         $client
     * @param RequestFactory          $requestFactory
     * @param ResponseMapperInterface $responseMapper
     * @param Registry                $metadataRegistry
     */
    public function __construct(
        ClientInterface $client,
        RequestFactory $requestFactory,
        ResponseMapperInterface $responseMapper,
        Registry $metadataRegistry
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->responseMapper = $responseMapper;
        $this->metadataRegistry = $metadataRegistry;
    }

    /**
     * @param string $class
     *
     * @return RepositoryInterface
     */
    public function getRepository($class)
    {
        if (!class_exists($class)) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" does not exist or could not be autoloaded.', $class)
            );
        }

        if (!isset($this->repositories[$class])) {
            $this->repositories[$class] = $this->createRepository($class);
        }

        return $this->repositories[$class];
    }

    /**
     * @param $class
     *
     * @return RepositoryInterface
     */
    private function createRepository($class)
    {
        $metadata = $this->metadataRegistry->getMetadataForClass($class);
        $repositoryClass = $metadata->getRepositoryClass();

        $repository = new $repositoryClass($this->client, $this->requestFactory, $this->responseMapper, $class);

        if (!$repository instanceof RepositoryInterface) {
            throw new InvalidArgumentException(
                sprintf('Repositories must implement "Tystr\RestOrm\Repository\RepositoryInterface')
            );
        }

        return $repository;
    }
}
