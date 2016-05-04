<?php

namespace Tystr\RestOrm\Repository;

use GuzzleHttp\ClientInterface;
use Tystr\RestOrm\Request\Factory;
use Tystr\RestOrm\Response\ResponseMapperInterface;

/**
 * Custom child repository
 *
 * @author John Pancoast <john.p@zeeto.io>
 */
class ChildRepository extends Repository
{
    private $customDependency;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ClientInterface $client,
        Factory $requestFactory,
        ResponseMapperInterface $responseMapper,
        $class,
        $customDependency = null
    ) {
        parent::__construct($client, $requestFactory, $responseMapper, $class);
        $this->customDependency = $customDependency;
    }

    /**
     * Get model class this repo is working with
     *
     * Here for tests
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->class;
    }

    /**
     * Get custom dependency
     *
     * Here for testing
     *
     * @return mixed
     */
    public function getCustomDependency()
    {
        return $this->customDependency;
    }
}
