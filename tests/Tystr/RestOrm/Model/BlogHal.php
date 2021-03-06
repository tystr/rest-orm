<?php

namespace Tystr\RestOrm\Model;

use JMS\Serializer\Annotation\Type;
use Tystr\RestOrm\Annotation\Embedded;
use Tystr\RestOrm\Annotation\Hal;
use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @Resource("blogs")
 * @Hal(embeddedRel="blogs")
 */
class BlogHal
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

    /**
     * @Type("array<Tystr\RestOrm\Model\Comment>")
     */
    public $comments;
}
