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
