<?php

/**
 * ルーターを使ったルーティング
 *
 * 簡単なルーターを使ったアプリです
 *
 * @copyright 2017 USAMI Kenta
 * @license https://opensource.org/licenses/MIT MIT
 */

require_once __DIR__ . '/../vendor/autoload.php';

session_start();


$routing_map = [
    ['GET', '/', 'action_show_index'],
    ['GET', '/login', 'action_show_login'],
    ['POST', '/login', 'action_post_login'],
    ['GET', '/logout', 'action_show_logout'],
    ['POST', '/logout', 'action_post_logout'],
    '#404' => 'action_generic_not_found',
];

// ルーターを起動する
$router = new \Teto\Routing\Router($routing_map);
$action = $router->match($_SERVER['REQUEST_METHOD'], explode('?', $_SERVER['REQUEST_URI'], 2)[0]);
call_user_func($action->value);

exit(0);

/**
 * GET /login
 */
function action_show_index()
{ ?><!DOCTYPE HTML>
<title>トップページ</title>
<h1>トップページ</h1>
<?php if (is_logged_in()): ?>
    <p>あなたは<?= h($_SESSION['user_id']) ?>さんですね</p>
    <div><a href="/logout">ログアウト</a></div>
<?php else: ?>
    <p><a href="/login">ログインしてね</a></p>
<?php endif; ?>
<?php }

/**
 * GET /login
 */
function action_show_login()
{ ?><!DOCTYPE HTML>
<h1>ログインフォーム</h1>
<p><code>nimda</code> / <code>PassWord</code></p>
<form action="/login" method="post">
    <label>ID</label><input name="id">
    <label>password</label><input name="password" type="password">
    <button type="submit">ログイン</button>
</form>
<?php }

/**
 * GET /logout
 */
function action_show_logout()
{ ?><!DOCTYPE HTML>
<h1>ログインフォーム</h1>
<p>ログアウトしますか</p>
<form action="/logout" method="post">
    <button type="submit">ログアウト</button>
</form>
<?php }

/**
 * POST /logout
 */
function action_post_logout()
{
    unset($_SESSION['user_id']);
    header('Location: /', true, 302);
    return;
}

/**
 * GET /login
 */
function action_post_login()
{
    $input_id = filter_input(INPUT_POST, 'id');
    $input_password = filter_input(INPUT_POST, 'password');

    if ($user_id = user_verify($input_id, $input_password)) {
        $_SESSION['user_id'] = $user_id;
        session_regenerate_id(true);
        header('Location: /', true, 302);
        return;
    }

    action_show_login();
}

function action_generic_not_found()
{ ?><!DOCTYPE HTML>
<h1>404 Not Found</h1>
<p>ないよ</p>
<p><a href="/">インデックス</a></p>
<?php
    http_response_code(404);
}

// Utilities

function h($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * @param  string $input_id
 * @param  string $input_password
 * @return string|false
 */
function user_verify($input_id, $input_password)
{
    if ($input_id === 'nimda' && $input_password === 'PassWord') {
        return 'nimda';
    }

    return false;
}

/**
 * @param  int $work_id
 * @return array|false
 */
function find_work($work_id)
{
    if ($work_id == 123) {
        return "/image/123.gif";
    }

    return false;
}
