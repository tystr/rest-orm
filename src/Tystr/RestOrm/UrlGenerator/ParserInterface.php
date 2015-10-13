<?php

namespace Tystr\RestOrm\UrlGenerator;

/**
 * Contract for classes implementing url parsing logic.
 *
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface ParserInterface
{
    /**
     * This method should accept a string similar to the following:
     *
     * /categories/{{categoryId}}/posts
     *
     * The $requirements argument should contain an array of variables that should be interpolated:
     *
     * array("categoryId" => 42)
     *
     * @param string $string
     * @param array  $requirements
     *
     * @return string
     */
    public function parse($string, array $requirements);
}
