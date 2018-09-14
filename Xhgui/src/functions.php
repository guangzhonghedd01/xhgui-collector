<?php
/**
 * @author: Gilbert He <guangzhonghe@hk01.com>
 * Date: 2018/9/14
 * Time: 2:51 PM
 * FILENAME:functions.php
 */

namespace Guangzhong\Xhgui;

if (!\function_exists('xhgui_laravel')) {
    function xhgui_laravel()
    {
        Import::laravel();
    }
}
