<?php

namespace Tystr\RestOrm\Repository;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface RepositoryFactoryInterface
{
    /**
     * @param string $class
     *
     * @return RepositoryInterface
     */
    public function getRepository($class);
}
