<?php

namespace Tystr\RestOrm\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 */
final class Hal extends Annotation
{
    public $embeddedRel;
}
