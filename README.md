# RestOrm
[![Build Status](https://travis-ci.org/tystr/rest-orm.svg?branch=master)](https://travis-ci.org/tystr/rest-orm)
[![Test Coverage](https://codeclimate.com/github/tystr/rest-orm/badges/coverage.svg)](https://codeclimate.com/github/tystr/rest-orm/coverage)
[![Packagist](https://img.shields.io/packagist/v/tystr/rest-orm.svg)](https://packagist.org/packages/tystr/rest-orm)
[![Downloads](https://img.shields.io/packagist/dt/tystr/rest-orm.svg)](https://packagist.org/packages/tystr/rest-orm)

_**Note: as of `v0.6.0` this library depends on `PHP 7.x`.**_

A simple ORM-like package for handling object persistence using a RESTful API.

# Installation
Install `tystr/rest-orm` with composer:

    # composer.phar require tystr/rest-orm:~0.1

# Configuration
For each of your models you need to add mapping configuration and set up a `Repository` instance.

## Mapping
RestOrm provides 2 annotations which must be used on each model:

* `@Resource` This configures the name of the rest resource to use when generating urls.
* `@Id` This configures the property to use as the identifier.

Data is hydrated into the models using [JMS Serializer](http://jmsyst.com/libs/serializer). See the documentation
[here](http://jmsyst.com/libs/serializer/master/reference) for information on how to add mapping for your models.

```PHP
<?php

use Tystr\RestOrm\Annotation\Resource;
use Tystr\RestOrm\Annotation\Id;
use JMS\Serializer\Annotation\Type;

/**
 * @Resource("blogs")
 */
class Blog
{
    /**
     * @Id
     * @Type("integer")
     */
    protected $id;

    /**
     * @Type("string")
     */
    protected $title;

    /**
     * @Type("string")
     */
    protected $body;

    /**
     * @Type("datetime")
     */
    protected $lastModified;

    // ... Accessor methods
}
```

## Configure the Repository

Now that your models are mapped, you need to configure a `Tystr\RestOrm\Repository\Repository` instance for each of your
models.

First, configure the guzzle client that will be used to make request to your API:
```PHP
// Configure any auth headers when instantiating the guzzle client. These will be passed in each request.
$headers = [
    'Authorization' => 'Token 23a65de8ea1f2b52defea12c0d7a9c11'
];
$client = new GuzzleHttp\Client(['headers' => $headers]);

```

Next, set up the `RequestFactory`:
```PHP
$urlGenerator = new Tystr\RestOrm\UrlGenerator\StandardUrlGenerator('https://example.com/api');
$format = 'json'; // either 'json' or 'xml'

$requestFactory = new Tystr\RestOrm\Request\Factory($urlGenerator, $format);
```

Now instantiate a Response Mapper. RestOrm currently provides 2 types of response mappers:
* `Tystr\RestOrm\Response\StandardResponseMapper` for basic JSON serialization/deserialization
* `Tystr\RestOrm\Response\HalResponseMapper` for [HAL](http://stateless.co/hal_specification.html)-formatted APIs

```PHP
$responseMapper = new Tystr\RestOrm\Response\StandardResponseMapper();
```

Finally, instantiate a Repository class for each of your models:
```PHP
// Instantiate a repository.
$class = 'Your\Project\Blog\Post';
$postRepository = new Tystr\RestOrm\Repository\Repository($client, $requestFactory, $responseMapper, $class);
```


# Usage

The `Tystr\RestOrm\Repository\RepositoryInterface` currently provides 4 basic methods for interacting with your models:
* `save($model)`
* `findOneById($id)`
* `findAll()`
* `remove($model)`
