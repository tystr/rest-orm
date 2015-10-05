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
     *
     * @return object
     */
    public function save($object, $mapResponse = false, array $parameters = []);

    /**
     * @param string|int $id
     * @param array      $parameters
     *
     * @return object
     */
    public function findOneById($id, array $parameters = []);

    /**
     * @param array $parameters
     *
     * @return array|object[]
     */
    public function findAll(array $parameters = []);

    /**
     * @param object $object
     * @param array  $parameters
     *
     * @return bool
     */
    public function remove($object, array $parameters);
}
