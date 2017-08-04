<?php

require_once __DIR__ . '/../vendor/autoload.php';

// ブラウザのXSS防護機能を意図的に無効にしています
header('X-XSS-Protection: 0');
// ↑ 本番サービスでは絶対に設定しないでください

$page_user = filter_input(INPUT_GET, 'user');
$users = [
    '太郎',
    '"><script>alert(1);</script>'
];
?>
<!DOCTYPE html>
<title>XSS</title>
<?php if ($page_user === null): ?>
    <h1>XSSポータル</h1>
<?php elseif ($page_user === false):
http_response_code(404);
?>
    <h1>404 Not Found</h1>
<?php else: ?>
    <h1><?= $page_user ?>さんのページ</h1>
    <p>↑ ここは危険な出力方法</p>
<?php endif; ?>

<p><mark>このページには脆弱性が含まれる</mark>ので、参考にする際は細心の注意を払ってください</p>
<hr>
<h2>ページ一覧</h2>
<p>↓ ここは安全な出力方法</p>
<ul>
    <li><a href="?">XSSトップ</a></li>
<?php foreach ($users as $user): ?>
    <li><a href="?<?= http_build_query(['user' => $user]) ?>">
        <?= htmlspecialchars($user, ENT_QUOTES) ?>さんのページ
    </a></li>
<?php endforeach; ?>
</ul>
