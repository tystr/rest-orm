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
    public function getCreateUrl($resource, array $parameters = [])
    {
        return $this->getResourceCollectionUrl($resource, $parameters);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getModifyUrl($resource, $id, array $parameters = [])
    {
        return $this->getResourceUrl($resource, $id, $parameters);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getFindOneUrl($resource, $id, array $parameters = [])
    {
        return $this->getResourceUrl($resource, $id, $parameters);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    public function getFindAllUrl($resource, array $parameters = [])
    {
        return $this->getResourceCollectionUrl($resource, $parameters);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getRemoveUrl($resource, $id, array $parameters = [])
    {
        return $this->getResourceUrl($resource, $id, $parameters);
    }

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    private function getResourceUrl($resource, $id, array $parameters)
    {
        return $this->appendQueryString($this->baseUrl.'/'.$resource.'/'.$id, $parameters);
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    private function getResourceCollectionUrl($resource, array $parameters)
    {
        return $this->appendQueryString($this->baseUrl.'/'.$resource, $parameters);
    }

    /**
     * @param string $url
     * @param array  $parameters
     *
     * @return string
     */
    private function appendQueryString($url, array $parameters)
    {
        $url .= empty($parameters) ? '' : '?'.http_build_query($parameters);

        return $url;
    }
}
