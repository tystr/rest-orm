<?php

namespace Tystr\RestOrm\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Tystr\RestOrm\Repository\Repository;

/**
 * @Annotation
 */
final class Resource extends Annotation
{
    public $repositoryClass = Repository::class;
}
