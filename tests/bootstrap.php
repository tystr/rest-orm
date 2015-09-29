<?php

$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->addPsr4('Tystr\\RestOrm\\', __DIR__.'/Tystr/RestOrm');

use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

