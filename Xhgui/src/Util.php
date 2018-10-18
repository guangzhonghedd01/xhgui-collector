<?php
namespace Guangzhong\Xhgui;

class Util
{
    /**
     * Creates a simplified URL given a standard URL.
     * Does the following transformations:
     *
     * - Remove numeric values after =.
     *
     * @param string $url
     * @return string
     */
    public static function simpleUrl($url)
    {
        return preg_replace('/\=\d+/', '', $url);
    }

}
