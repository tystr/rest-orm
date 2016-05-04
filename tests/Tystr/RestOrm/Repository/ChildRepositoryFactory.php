<?php

namespace Tystr\RestOrm\Repository;

use GuzzleHttp\ClientInterface;
use Tystr\RestOrm\Metadata\Registry;
use Tystr\RestOrm\Request\Factory as RequestFactory;
use Tystr\RestOrm\Response\ResponseMapperInterface;

/**
 * A child repository factory to test that repository factories work right while extending parent
 *
 * @author John Pancoast <john.p@zeeto.io>
 */
class ChildRepositoryFactory extends RepositoryFactory
{
    private $customDependency;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ClientInterface $client,
        RequestFactory $requestFactory,
        ResponseMapperInterface $responseMapper,
        Registry $metadataRegistry,
        $customRepoDependency
    ) {
        parent::__construct($client, $requestFactory, $responseMapper, $metadataRegistry);
        $this->customDependency = $customRepoDependency;
    }

    /**
     * @inheritDoc
     */
    protected function instantiateRepository($repositoryClass, $modelClass)
    {
        // note add'l dependency
        return new $repositoryClass(
            $this->client,
            $this->requestFactory,
            $this->responseMapper,
            $modelClass,
            $this->customDependency
        );
    }
}
