<?php

/**
 * whoopsの有効/無効をスイッチできるサンプルアプリです
 *
 * @copyright 2017 USAMI Kenta
 * @license https://opensource.org/licenses/MIT MIT
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

if (isset($_GET['enable'])) {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

foo("hoge");
exit;

function foo($v)
{
    if (true) {
        bar($v);
    }
}

function bar()
{
    call_user_func('buz', "fizzbuzz");
}

function buz()
{
    $v = [];

    return 1 + $v;
}
