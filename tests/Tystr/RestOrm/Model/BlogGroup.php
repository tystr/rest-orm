<?php

namespace Tystr\RestOrm\Model;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @Resource("blogs")
 */
class BlogGroup
{
    /**
     * @Id()
     * @Type("integer")
     * @Groups("READ")
     */
    public $id;

    /**
     * @Type("string")
     */
    public $body;
}
