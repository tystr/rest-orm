<?php

namespace Tystr\RestOrm\Model;

use JMS\Serializer\Annotation\Type;
use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @Resource("blogs")
 */
class Blog
{
    /**
     * @Id()
     * @Type("integer")
     */
    public $id;

    /**
     * @Type("string")
     */
    public $body;
}