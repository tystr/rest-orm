<?php

namespace Tystr\RestOrm\UrlGenerator;

/**
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
class Parser implements ParserInterface
{
    /**
     * @param string $string
     * @param array  $requirements
     *
     * @return string
     */
    public function parse($string, array $requirements)
    {
        $matches = [];
        preg_match_all('/(\{\{[^}]*\}\})+/', $string, $matches);

        foreach ($matches[1] as $token) {
            $param = str_replace(['{{','}}', ' '], '', $token);
            if (!isset($requirements[$param])) {
                throw new \InvalidArgumentException();
            }

            $string = str_replace($token, $requirements[$param], $string);
        }

        return $string;
    }
}
