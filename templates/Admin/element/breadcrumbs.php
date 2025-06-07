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
<?php if ($show === 'self_info_detail') { ?>
    <a href="<?= $topPageUrl ?>">TOP</a>
    &nbsp; >> &nbsp; <b>ログイン管理者情報</b>
<?php } ?>

<?php if ($show === 'self_info_detail-account_info_edit') { ?>
    <a href="<?= $topPageUrl ?>">TOP</a>
    &nbsp; >> &nbsp; <a href="<?= $this->Url->build([
        'admin_account_id' => $admin_account_id,
        'prefix' => 'Admin/SelfInfo',
        'controller' => 'Detail',
        'action' => 'index',
    ]) ?>">ログイン管理者情報</a>
    &nbsp; >> &nbsp; <b>アカウント情報更新</b>
<?php } ?>

    