<?php


?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        システムタイトル(管理者ページ):
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <?= $this->Html->css(['normalize.min', 'milligram.min', ]) ?>

    <link href="/css/form.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="top-nav-title">
            <a href="<?= $this->Url->build('/ad/') ?>">システムタイトル(管理者ページ)</a>
        </div>
        <div class="top-nav-links">
        </div>
    </header>
    <main class="main">
        <div class="container">
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
        ©︎hanahubuki.jp
    </footer>
</body>
</html>
