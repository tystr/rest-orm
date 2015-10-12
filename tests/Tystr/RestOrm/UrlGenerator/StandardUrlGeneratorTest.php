<?php

namespace Tystr\RestOrm\UrlGenerator;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class StandardUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    protected $generator;

    public function setUp()
    {
        $this->generator = new StandardUrlGenerator();
    }

    /**
     * @dataProvider getData
     */
    public function testGetCreateUrl($resource, $parameters, $expectedQueryString)
    {
        $url = $this->generator->getCreateUrl($resource, $parameters);
        $this->assertEquals('/cars'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getData
     */
    public function testGetModifyUrl($resource, $parameters, $expectedQueryString)
    {
        $url = $this->generator->getModifyUrl($resource, 42, $parameters);
        $this->assertEquals('/cars/42'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getData
     */
    public function testGetFindOneUrl($resource, $parameters, $expectedQueryString)
    {
        $url = $this->generator->getFindOneUrl($resource, 12, $parameters);
        $this->assertEquals('/cars/12'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getData
     */
    public function testGetFindAllUrl($resource, $parameters, $expectedQueryString)
    {
        $url = $this->generator->getFindAllUrl($resource, $parameters);
        $this->assertEquals('/cars'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getDataWithUrlRequirements
     */
    public function testGetCreateUrlWithUrlRequirements($resource, $parameters, $requirements,  $expectedUrl, $expectedQueryString)
    {
        $url = $this->generator->getCreateUrl($resource, $parameters, $requirements);
        $this->assertEquals($expectedUrl.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getDataWithUrlRequirements
     */
    public function testGetModifyUrlWithUrlRequirements($resource, $parameters, $requirements,  $expectedUrl, $expectedQueryString)
    {
        $url = $this->generator->getModifyUrl($resource, 42, $parameters, $requirements);
        $this->assertEquals($expectedUrl.'/42'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getDataWithUrlRequirements
     */
    public function testGetFindOneWithUrlRequirements($resource, $parameters, $requirements,  $expectedUrl, $expectedQueryString)
    {
        $url = $this->generator->getFindOneUrl($resource, 42, $parameters, $requirements);
        $this->assertEquals($expectedUrl.'/42'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getDataWithUrlRequirements
     */
    public function testGetFindAllWithUrlRequirements($resource, $parameters, $requirements,  $expectedUrl, $expectedQueryString)
    {
        $url = $this->generator->getFindAllUrl($resource, $parameters, $requirements);
        $this->assertEquals($expectedUrl.$expectedQueryString, $url);
    }

    public function getData()
    {
        return [
            ['cars', [], ''],
            ['cars', ['limit' => 10, 'page' => 5], '?limit=10&page=5'],
        ];
    }

    public function getDataWithUrlRequirements()
    {
        $requirements = [
            'categoryId' => 'hello',
            'postId' => 'world',
        ];

        return [
            [
                'blogs/{{categoryId}}/posts/{{postId}}',
                [],
                $requirements,
                '/blogs/hello/posts/world',
                '',
            ],
            [
                'blogs/{{categoryId}}/posts/{{postId}}',
                ['limit' => 10, 'page' => 5],
                $requirements,
                '/blogs/hello/posts/world',
                '?limit=10&page=5',
            ],
        ];
    }
}

