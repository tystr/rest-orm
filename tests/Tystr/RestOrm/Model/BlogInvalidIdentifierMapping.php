<?php

namespace Tystr\RestOrm\Model;

use Tystr\RestOrm\Annotation\Id;

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