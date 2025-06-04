<?php
    use App\Lib\Auth\Admin\AuthInterface as AdminAuthInterface;
    
    $admin_account_id = $this->getRequest()->getParam('admin_account_id');
    /** @var AdminAuthInterface */
    $adminAuth = (function() use ($admin_account_id) : AdminAuthInterface {
        
        return $this->getRequest()->getSession()->read(ADMIN_AUTH_KEY . '.' . (string) $admin_account_id);
    })();
    
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

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-title">
            <a href="<?= $this->Url->build([
                'admin_account_id' => $admin_account_id,
                'prefix' => 'Admin',
                'controller' => 'Top',
                'action' => 'index',
            ]) ?>">システムタイトル(管理者ページ)</a>
        </div>
        <div class="top-nav-links">
            <a 
                href="<?= $this->Url->build([
                    'admin_account_id' => $admin_account_id,
                    'prefix' => 'Admin/SelfInfo',
                    'controller' => 'Detail',
                    'action' => 'index',
                ]) ?>"
            ><?= h($adminAuth->getName()) ?></a>
            <a 
                href="<?= $this->Url->build([
                    'admin_account_id' => $admin_account_id,
                    'prefix' => 'Admin/Auth',
                    'controller' => 'Logout',
                    'action' => 'index',
                ]) ?>"
                onclick="confirm('ログアウトしますか？');"
            >ログアウト</a>
        </div>
    </nav>
    <main class="main">
        <div class="container">
            <?= $this->fetch('content') ?>
        </div>
    </main>
    <footer>
    </footer>
</body>
</html>
