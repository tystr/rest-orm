<?php

namespace Tystr\RestOrm\Model;

use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @Resource("blogs")
 */
class Blog
{
    /**
     * @Id()
     */
    public $id;

    public $body;
}