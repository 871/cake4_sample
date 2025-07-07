<?php

/**
 * 管理者用サイドメニュー
 */

    $admin_account_id = $this->getRequest()->getParam('admin_account_id');
    
?>
    <aside class="column">
        <div class="side-nav">
            <a href="<?= $this->Url->build([
                'admin_account_id' => $admin_account_id,
                'prefix' => 'Admin',
                'controller' => 'Top',
                'action' => 'index',
            ]) ?>" class="side-nav-item">TOP</a>
            
            <a href="<?= $this->Url->build([
                'admin_account_id' => $admin_account_id,
                'prefix' => 'Admin/UserAccounts',
                'controller' => 'Search',
                'action' => 'init',
            ]) ?>" class="side-nav-item">ユーザ管理</a>
            
        </div>
    </aside>