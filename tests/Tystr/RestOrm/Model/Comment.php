<?php

namespace Tystr\RestOrm\Model;

use JMS\Serializer\Annotation\Type;
use Tystr\RestOrm\Annotation\Id;
use Tystr\RestOrm\Annotation\Resource;

class Comment
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
     * @Type("string")
     */
    public $author;

    public function __construct($id, $body)
    {
        $this->id = $id;
        $this->body = $body;
    }
}

