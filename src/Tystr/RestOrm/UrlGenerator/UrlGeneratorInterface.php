<?php

namespace Tystr\RestOrm\UrlGenerator;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface UrlGeneratorInterface
{
    /**
     * @param string $resource
     * @param array  $parameters
     *
     * @return string
     */
    public function getCreateUrl($resource, array $parameters = []);

    /**
     * @param string $resource
     * @param string $id
     * @param array  $parameters
     *
     * @return string
     */
    public function getModifyUrl($resource, $id, array $parameters = []);

    /**
     * @param string $resource
     * @param string $id
     * @param array  $parameters
     *
     * @return string
     */
    public function getFindOneUrl($resource, $id, array $parameters = []);

    /**
     * @param string $resource
     * @param array  $parameters
     *
     * @return string
     */
    public function getFindAllUrl($resource, array $parameters = []);
}
