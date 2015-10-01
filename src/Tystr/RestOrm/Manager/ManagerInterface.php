<?php

namespace Tystr\RestOrm\Manager;

/**
 * Contract for classes responsible for managing mapped models.
 *
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface ManagerInterface
{
    /**
     * @param object $object
     *
     * @return object
     */
    public function save($object);

    /**
     * @param string|int $id
     *
     * @return object
     */
    public function findOneById($id);

    /**
     * @return array|object[]
     */
    public function findAll();

    /**
     * @param object $object
     *
     * @return bool
     */
    public function remove($object);
}
