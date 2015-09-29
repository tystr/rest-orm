<?php

namespace Tystr\RestOrm\UrlGenerator;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface UrlGeneratorInterface
{
    /**
     * @param string $resource
     *
     * @return string
     */
    public function getCreateUrl($resource);

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getModifyUrl($resource, $id);

    /**
     * @param string $resource
     * @param string $id
     *
     * @return string
     */
    public function getFindOneUrl($resource, $id);

    /**
     * @param string $resource
     *
     * @return string
     */
    public function getFindAllUrl($resource);
}
