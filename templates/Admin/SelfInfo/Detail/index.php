<?php


    $admin_account_id = $this->getRequest()->getParam('admin_account_id');
?>
<div class="row">
    <?= $this->element('left_menu') ?>
    <div class="main_menu column-responsive column-80">
        <div class="row">
            <div class="column">
                <?= $this->element('top_menu', ['show' => 'self_info_detail']) ?>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <?= $this->element('breadcrumbs', ['show' => 'self_info_detail']) ?>
            </div>
        </div>
        <h2>ログイン管理者情報</h2>
        <?= $this->Flash->render() ?>
        <div class="row">
            <div class="column">
                <h3>アカウント情報</h3>
                <div class="detail_result">
                    <dl>
                        <dt>管理者ID</dt>
                        <dd><?= h($adminAccount['id'] ?? '---') ?></dd>
                        <dt>ログインアカウント</dt>
                        <dd><?= h($adminAccount['username'] ?? '---') ?></dd>
                        <dt>表示名</dt>
                        <dd><?= h($adminAccount['name'] ?? '---') ?></dd>
                        <dt>メールアドレス</dt>
                        <dd><?= h($adminAccount['email'] ?? '---') ?></dd>
                        <dt>電話番号</dt>
                        <dd><?= h($adminAccount['tel'] ?? '---') ?></dd>
                        <dt>作成日時</dt>
                        <dd><?= h($adminAccount['created']?->format('Y/m/d H:i:s') ?? '----/--/-- --:--:--') ?></dd>
                        <dt>更新日時</dt>
                        <dd><?= h($adminAccount['modified']?->format('Y/m/d H:i:s') ?? '----/--/-- --:--:--') ?></dd>
                    </dl>
                    <a href="<?= $this->Url->build([
                        'admin_account_id' => $admin_account_id,
                        'prefix' => 'Admin/SelfInfo/AccountInfo',
                        'controller' => 'Edit',
                        'action' => 'index',
                    ]) ?>" class="button">アカウント情報更新</a>
                </div>
            </div>
            <div class="column">
                <h3>パスワード情報</h3>
                <div class="detail_result">
                    <dl>
                        <dt>パスワード</dt>
                        <dd>(非表示)</dd>
                        <dt>パスワード有効期限</dt>
                        <dd><?= h($adminAccount['expiration_datetime']?->format('Y/m/d H:i:s') ?? '----/--/-- --:--:--') ?></dd>
                    </dl>
                    <a href="<?= $this->Url->build([
                        'admin_account_id' => $admin_account_id,
                        'prefix' => 'Admin/SelfInfo/Password',
                        'controller' => 'Edit',
                        'action' => 'index',
                    ]) ?>" class="button">パスワード情報更新</a>
                </div>
            </div>
        </div>
    </div>
</div>