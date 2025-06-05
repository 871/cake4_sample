<?php


?>
    <h2>管理者ログイン</h2>
<?= $this->Flash->render() ?>
<?php if ($authErrorMessage) { ?>
    <div class="message error">
        <?= h($authErrorMessage) ?>
    </div>
<?php } ?>
    <form method="post">
        <input type="hidden" name="_csrfToken" value="<?= h($this->request->getAttribute('csrfToken')) ?>" />
        <input type="text" name="username" value="<?= h($input['username']) ?>" placeholder="ログインアカウント">
        <input type="password" name="password" value="" placeholder="パスワード">
        <input type="submit" value="ログイン">
    </form>