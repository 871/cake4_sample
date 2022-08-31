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
            <form method="post" action="<?= $this->Url->build(['action' => 'step02Post']) ?>">
                <input type="hidden" name="_csrfToken" autocomplete="off" value="<?= $this->request->getAttribute('csrfToken') ?>">
                
                <fieldset>
                    <legend><?= __('Customer Registration') ?></legend>
                    <input type="password" name="password" value="<?= h($data['password']?? '') ?>" />
                    <?= join("\n", array_map(function($msg) {
                        return sprintf('<p class="message error">%s</p>', h($msg));
                    }, $errors['password']?? [])) ?>
                    
                </fieldset>
                <input type="submit" valuw="<?= __('Submit') ?>"/>
            </form>
        </div>
    </div>
</div>
