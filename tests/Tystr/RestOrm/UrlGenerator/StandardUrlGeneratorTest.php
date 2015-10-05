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
     * @dataProvider getParameters
     */
    public function testGetCreateUrl($parameters, $expectedQueryString)
    {
        $url = $this->generator->getCreateUrl('cars', $parameters);
        $this->assertEquals('/cars'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getParameters
     */
    public function testGetModifyUrl($parameters, $expectedQueryString)
    {
        $url = $this->generator->getModifyUrl('cars', 42, $parameters);
        $this->assertEquals('/cars/42'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getParameters
     */
    public function testGetFindOneUrl($parameters, $expectedQueryString)
    {
        $url = $this->generator->getFindOneUrl('cars', 12, $parameters);
        $this->assertEquals('/cars/12'.$expectedQueryString, $url);
    }

    /**
     * @dataProvider getParameters
     */
    public function testGetFindAllUrl($parameters, $expectedQueryString)
    {
        $url = $this->generator->getFindAllUrl('cars', $parameters);
        $this->assertEquals('/cars'.$expectedQueryString, $url);
    }

    public function getParameters()
    {
        return [
            [[], ''],
            [['limit' => 10, 'page' => 5], '?limit=10&page=5'],
        ];
    }
}

