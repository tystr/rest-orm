<?php

namespace Tystr\RestOrm\UrlGenerator;

use PHPUnit\Framework\TestCase;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class ParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser();
    }

    public function testParse()
    {
        $string = '/categories/{{categoryId}}/items/{{itemId}}/test';
        $expected = '/categories/123/items/456/test';

        $requirements = ['categoryId' => '123', 'itemId' => '456'];
        $parsed = $this->parser->parse($string, $requirements);
        $this->assertEquals($expected, $parsed);
    }
}