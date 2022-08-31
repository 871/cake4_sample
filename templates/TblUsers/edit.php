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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $tblUser->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $tblUser->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Tbl Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tblUsers form content">
            <?= $this->Form->create($tblUser) ?>
            <fieldset>
                <legend><?= __('Edit Tbl User') ?></legend>
                <?php
                    echo $this->Form->control('username');
                    echo $this->Form->control('password');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
