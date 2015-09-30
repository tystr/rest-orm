<?php

namespace Tystr\RestOrm\Model;

use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @Resource("blogs")
 */
class BlogInvalidIdentifierMapping
{
    /**
     * @Id()
     */
    public $id;

    /**
     * @Id()
     */
    public $id2;
}
