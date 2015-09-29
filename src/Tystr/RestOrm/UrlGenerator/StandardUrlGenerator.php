<?php

namespace Tystr\RestOrm\UrlGenerator;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class StandardUrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    public function getCreateUrl($resource)
    {
        return $this->getResourceCollectionUrl($resource);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getModifyUrl($resource, $id)
    {
        return $this->getResourceUrl($resource, $id);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getFindOneUrl($resource, $id)
    {
        return $this->getResourceUrl($resource, $id);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    public function getFindAllUrl($resource)
    {
        return $this->getResourceCollectionUrl($resource);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getRemoveUrl($resource, $id)
    {
        return $this->getResourceUrl($resource, $id);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    private function getResourceUrl($resource, $id)
    {
        return $this->baseUrl.'/'.$resource.'/'.$id;
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    private function getResourceCollectionUrl($resource)
    {
        return $this->baseUrl.'/'.$resource;
    }
}
