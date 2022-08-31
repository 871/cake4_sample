<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TblUser $tblUser
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Tbl Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tblUsers form content">
            <form method="post" action="<?= $this->Url->build(['action' => 'confPost']) ?>">
                <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
                
                <fieldset>
                    <legend><?= __('Customer Registration') ?></legend>
                    
                    <p>username</p>
                    <p><?= h($data['username']) ?></p>
                    <p>password</p>
                    <p><?= h($data['password']) ?></p>
                    
                </fieldset>
                <input type="submit" valuw="<?= __('Submit') ?>"/>
            </form>
        </div>
    </div>
</div>
