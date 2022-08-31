<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TblUser[]|\Cake\Collection\CollectionInterface $tblUsers
 */
?>
<div class="tblUsers index content">
    <?= $this->Html->link(__('New Tbl User'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Tbl Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('username') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tblUsers as $tblUser): ?>
                <tr>
                    <td><?= $this->Number->format($tblUser->id) ?></td>
                    <td><?= h($tblUser->username) ?></td>
                    <td><?= h($tblUser->created) ?></td>
                    <td><?= h($tblUser->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $tblUser->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $tblUser->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $tblUser->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tblUser->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
