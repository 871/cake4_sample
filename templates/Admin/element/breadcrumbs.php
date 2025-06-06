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
    <a href="<?= $topPageUrl ?>">TOP</a>
<?php if ($show === 'self_info') { ?>
    &nbsp; >> &nbsp; <b>ログイン管理者情報</b>
<?php } ?>
<?php if ($show === 'self_info_account_info') { ?>
    &nbsp; >> &nbsp; <a href="<?= $this->Url->build([
        'admin_account_id' => $admin_account_id,
        'prefix' => 'Admin/SelfInfo',
        'controller' => 'Detail',
        'action' => 'index',
    ]) ?>">ログイン管理者情報</a>
    &nbsp; >> &nbsp; <b>アカウント情報更新</b>
<?php } ?>

    