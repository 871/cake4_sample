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
            
        </div>
    </aside>