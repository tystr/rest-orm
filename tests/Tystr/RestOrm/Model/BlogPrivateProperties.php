<?php

namespace Tystr\RestOrm\Model;

use JMS\Serializer\Annotation\Type;
use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @Resource("blogs", repositoryClass="Tystr\RestOrm\Repository\BlogRepository")
 */
class BlogPrivateProperties
{
    /**
     * @Id()
     * @Type("integer")
     */
    private $id;

    /**
     * @Type("string")
     */
    private $body;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}

