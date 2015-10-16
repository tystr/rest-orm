<?php

namespace Tystr\RestOrm\Model;

use JMS\Serializer\Annotation\Type;
use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

/**
 * @Resource("blogs/{{blogId}}/posts", repositoryClass="Tystr\RestOrm\Repository\PostRepository")
 */
class Post
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
