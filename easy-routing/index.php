<?php

/**
 * 愚直ルーティング
 *
 * 単純な if-else を組み合せたルーティングです
 *
 * @copyright 2017 USAMI Kenta
 * @license https://opensource.org/licenses/MIT MIT
 */

session_start();

$path_parts = explode('/', substr(explode('?', $_SERVER['REQUEST_URI'], 2)[0], 1));
$is_get_request = in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD']);
$is_post_request = $_SERVER['REQUEST_METHOD'] === 'POST';
if (!($is_get_request || $is_post_request)) {
    http_response_code(405);
    exit;
}

if ($path_parts[0] === '') {
    action_show_index();
    return;
} elseif ($path_parts[0] === 'view' && isset($path_parts[1]) && filter_var($path_parts[1], FILTER_VALIDATE_INT)) {
    $work_id = $path_parts[1];
    if ($work = find_work($work_id)) {
        action_display_works();
        return;
    }
} elseif ($path_parts[0] === 'login') {
    if (is_logged_in()) {
        header('Location: /', true, 302);
        return;
    }

    if ($is_post_request) {
        $input_id = filter_input(INPUT_POST, 'id');
        $input_password = filter_input(INPUT_POST, 'password');

        action_post_login($input_id, $input_password);
    } else {
        action_show_login();
    }

    return;
} elseif ($path_parts[0] === 'logout') {
    if (!is_logged_in()) {
        header('Location: /', true, 302);
        return;
    }

    if ($is_post_request) {
        unset($_SESSION['user_id']);
        session_regenerate_id(true);
        header('Location: /', true, 302);
    } else {
        action_show_logout();
    }
    return;
}

action_generic_not_found();

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
 * GET /login
 */
function action_post_login($input_id, $input_password)
{
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
