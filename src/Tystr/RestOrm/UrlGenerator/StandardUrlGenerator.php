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
     * @var ParserInterface
     */
    private $parser;

    /**
     * @param string               $baseUrl
     * @param ParserInterface|null $parser
     */
    public function __construct($baseUrl = '', ParserInterface $parser = null)
    {
        $this->baseUrl = $baseUrl;
        $this->parser = $parser ?: new Parser();
    }

    /**
     * @param string $resource
     * @param array  $requirements
     *
     * @return string
     */
    public function getCreateUrl($resource, array $parameters = [], array $requirements = [])
    {
        return $this->getResourceCollectionUrl($resource, $parameters, $requirements);
    }

    /**
     * @param string $resource
     * @param string $id
     * @param array  $requirements
     *
     * @return string
     */
    public function getModifyUrl($resource, $id, array $parameters = [], array $requirements = [])
    {
        return $this->getResourceUrl($resource, $id, $parameters, $requirements);
    }

    /**
     * @param string $resource
     * @param string $id
     * @param array  $requirements
     *
     * @return string
     */
    public function getFindOneUrl($resource, $id, array $parameters = [], array $requirements = [])
    {
        return $this->getResourceUrl($resource, $id, $parameters, $requirements);
    }

    /**
     * @param string $resource
     * @param array  $requirements
     *
     * @return string
     */
    public function getFindAllUrl($resource, array $parameters = [], array $requirements = [])
    {
        return $this->getResourceCollectionUrl($resource, $parameters, $requirements);
    }

    /**
     * @param string $resource
     * @param string $id
     * @param array  $requirements
     *
     * @return string
     */
    public function getRemoveUrl($resource, $id, array $parameters = [], array $requirements = [])
    {
        return $this->getResourceUrl($resource, $id, $parameters, $requirements);
    }

    /**
     * @param string $resource
     * @param string $id
     * @param array  $requirements
     *
     * @return string
     */
    private function getResourceUrl($resource, $id, array $parameters, array $requirements = [])
    {
        $resource = $this->parser->parse($resource, $requirements);

        return $this->appendQueryString($this->baseUrl.'/'.$resource.'/'.$id, $parameters);
    }

    /**
     * @param string $resource
     * @param array  $requirements
     *
     * @return string
     */
    private function getResourceCollectionUrl($resource, array $parameters, array $requirements = [])
    {
        $resource = $this->parser->parse($resource, $requirements);

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
