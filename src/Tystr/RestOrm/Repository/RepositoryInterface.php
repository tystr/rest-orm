<?php

namespace Tystr\RestOrm\Repository;

/**
 * Contract for repositories responsible for handling mapped models.
 *
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface RepositoryInterface
{
    /**
     * @param object $object
     * @param bool   $mapResponse
     * @param array  $parameters
     * @param array  $requirements
     *
     * @return object
     */
    public function save($object, $mapResponse = false, array $parameters = [], array $requirements = []);

    /**
     * @param string|int $id
     * @param array      $parameters
     * @param array      $requirements
     *
     * @return object
     */
    public function findOneById($id, array $parameters = [], array $requirements = []);

    /**
     * @param array $parameters
     * @param array $requirements
     *
     * @return array|object[]
     */
    public function findAll(array $parameters = [], array $requirements = []);

    /**
     * @param object $object
     * @param array  $parameters
     * @param array  $requirements
     *
     * @return bool
     */
    public function remove($object, array $parameters, array $requirements = []);
}
