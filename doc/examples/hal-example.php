<?php

$loader = require __DIR__.'/../../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Configure any auth headers when instantiating the guzzle client. These will be passed in each request.
$headers = [
    'Authorization' => 'Token 23a65de8ea1f2b52defea12c0d7a9c11'
];
$client = new \GuzzleHttp\Client(['headers' => $headers]);
$serializer = \JMS\Serializer\SerializerBuilder::create()->build();

// The metadata registry holds information about the resource to use in the url and the identifier
$metadataRegistry = new \Tystr\RestOrm\Metadata\Registry();

// The request factory generates the request that will be sent to the API. Note that you must set the format to be used
// when serializing the models to build the request body.
$urlGenerator = new \Tystr\RestOrm\UrlGenerator\StandardUrlGenerator('https://example.com/api');
$requestFactory = new \Tystr\RestOrm\Request\Factory($metadataRegistry, $serializer, $urlGenerator, 'json');

// The response mapper maps api responses to objects
// Use the HalResponseMapper to correctly map HAL APIs
$responseMapper = new \Tystr\RestOrm\Response\HalResponseMapper();

// Instantiate a manager.
$class = 'Post';
$postManager = new \Tystr\RestOrm\Manager\Manager($client, $requestFactory, $responseMapper, $class);

// Find all posts
$posts = $manager->findAll();
foreach ($posts as $post) {
    // ...
}

// Find a single post by it's id
$post = $manager->findOneById('42');

// Modify an existing post
$post->title = 'New Title';
$manager->save($post);

// Delete a post
$manager->remove($post);


/**
 * @Tystr\RestOrm\Annotation\Resource("posts")
 */
class Post
{
    /**
     * @Tystr\RestOrm\Annotation\Id()
     * @JMS\Serializer\Annotation\Type("string")
     */
    public $id;

    /**
     * @JMS\Serializer\Annotation\Type("string")
     */
    public $title;

    /**
     * @JMS\Serializer\Annotation\Type("string")
     */
    public $body;

    /**
     * @JMS\Serializer\Annotation\Type("boolean")
     */
    public $published;

    /**
     * @JMS\Serializer\Annotation\Type("integer")
     */
    public $createdAt;

    /**
     * @JMS\Serializer\Annotation\Type("integer")
     */
    public $updatedAt;
}

