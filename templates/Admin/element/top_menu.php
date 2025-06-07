<?php

    /**
     * 管理者用サイドメニュー
     */
    $admin_account_id = $this->getRequest()->getParam('admin_account_id');
    $topPageUrl = $this->Url->build([
        'admin_account_id' => $admin_account_id,
        'prefix' => 'Admin',
        'controller' => 'Top',
        'action' => 'index',
    ]);
?>
<?php if (in_array($show, [
    'self_info_detail', 
    'self_info_detail-account_info_edit',
    'self_info_detail-password_edit',
], true)) { ?>
    <a href="<?= $topPageUrl ?>">TOP</a>
    &nbsp; | &nbsp;
    <a href="<?= $this->Url->build([
        'admin_account_id' => $admin_account_id,
        'prefix' => 'Admin/SelfInfo',
        'controller' => 'Detail',
        'action' => 'index',
    ]) ?>">ログイン管理者情報</a>
    &nbsp; | &nbsp;
    <a href="<?= $this->Url->build([
        'admin_account_id' => $admin_account_id,
        'prefix' => 'Admin/SelfInfo/AccountInfo',
        'controller' => 'Edit',
        'action' => 'index',
    ]) ?>">アカウント情報更新</a>
    &nbsp; | &nbsp;
    <a href="<?= $this->Url->build([
        'admin_account_id' => $admin_account_id,
        'prefix' => 'Admin/SelfInfo/Password',
        'controller' => 'Edit',
        'action' => 'index',
    ]) ?>">パスワード情報更新</a>
<?php } ?>