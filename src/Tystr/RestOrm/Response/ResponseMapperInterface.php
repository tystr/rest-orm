<?php

namespace Tystr\RestOrm\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Contract for classes that map a PSR-7 Response object to a class
 *
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface ResponseMapperInterface
{
    /**
     * Maps a response body to an object
     *
     * @param ResponseInterface $response
     * @param string            $class
     * @param string            $format
     *
     * @return object
     */
    public function map(ResponseInterface $response, $class, $format);
}
