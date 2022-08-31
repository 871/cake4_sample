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
            <?= $this->Html->link(__('Edit Tbl User'), ['action' => 'edit', $tblUser->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Tbl User'), ['action' => 'delete', $tblUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tblUser->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Tbl Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Tbl User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tblUsers view content">
            <h3><?= h($tblUser->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Username') ?></th>
                    <td><?= h($tblUser->username) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($tblUser->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($tblUser->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($tblUser->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
