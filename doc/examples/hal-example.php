<?php

$loader = require __DIR__.'/../../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Configure any auth headers when instantiating the guzzle client. These will be passed in each request.
$headers = [
    'Authorization' => 'Token 23a65de8ea1f2b52defea12c0d7a9c11'
];
$client = new \GuzzleHttp\Client(['headers' => $headers]);

// The request factory generates the request that will be sent to the API. Note that you must set the format to be used
// when serializing the models to build the request body.
$urlGenerator = new \Tystr\RestOrm\UrlGenerator\StandardUrlGenerator('https://example.com/api');
$requestFactory = new \Tystr\RestOrm\Request\Factory($urlGenerator, 'json');

// The response mapper maps api responses to objects
// Use the HalResponseMapper to correctly map HAL APIs
$metadataRegistry = new \Tystr\RestOrm\Metadata\Registry();
$responseMapper = new \Tystr\RestOrm\Response\HalResponseMapper($metadataRegistry);

// The repository factory eases creation of repositories and acts as a registry for already created repositories.
$repositoryFactory = new \Tystr\RestOrm\Repository\RepositoryFactory($client, $requestFactory, $responseMapper, $metadataRegistry);

// Get a repository for the "Post" class.
$class = 'Post';
$repositoryFactory->getRepository($class);

// Find all posts
$posts = $repository->findAll();
foreach ($posts as $post) {
    // ...
}

// Find a single post by it's id
$post = $repository->findOneById('42');

// Modify an existing post
$post->title = 'New Title';
$repository->save($post);

// Delete a post
$repository->remove($post);


/**
 * @Tystr\RestOrm\Annotation\Resource("posts")
 */
class Post
{
    /**
     * @Tystr\RestOrm\Annotation\Id()
     * @JMS\Serializer\Annotation\Type("string")
     *
     * Adding any group (besides "Default") will ensure a property is not serialized when saving the object.
     * @JMS\Serializer\Annotation\Groups("READ_ONLY")
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

