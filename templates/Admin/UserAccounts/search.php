<?php

    $admin_account_id = $this->getRequest()->getParam('admin_account_id');

    $value = $this->getRequest()->getQuery('user_accounts', []);
    $error = $errors['user_accounts'] ?? [];

?>
<div class="row">
    <?= $this->element('left_menu') ?>
    <div class="column-responsive column-80">
        <div class="row">
            <div class="column">
                <?= $this->element('top_menu', ['show' => 'user_accounts-search']) ?>
            </div>
        </div>
        <h2>ユーザアカウント管理</h2>
        <?= $this->Flash->render() ?>
        <div class="row">
            <div class="column">
                <?= $this->element('breadcrumbs', ['show' => 'user_accounts-search']) ?>
            </div>
        </div>
    <?php if ($messages) { ?>
        <div class="row ">
            <div class="column message error">
                <?= join('<br>', array_map('h', $messages))?>
            </div>
        </div>
    <?php } ?>
        <div class="row">
            <div class="column">
                <form method="get">
                    <table>
                        <tr>
                            <th>ユーザアカウントID</th>
                            <td>
                                <input 
                                    type="text"
                                    name="user_accounts[id]"
                                    value="<?= h($value['id'] ?? '') ?>"
                                    class="<?= h($error['id'] ?? '') ?>"
                                >
                            </td>
                        </tr>
                    </table>
                    <input type="submit" value="検索">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <table>
                    <thead>
                        <tr>
                            <?php 
                                
                                $sort = [
                                    'url' => [
                                        'admin_account_id' => $admin_account_id,
                                        '?' => $this->getRequest()->getQuery(),
                                        'page' => 1,
                                    ],
                                ];
                            ?>
                            <th><?= $this->Paginator->sort('id', 'ID', $sort) ?></th>
                            <th><?= $this->Paginator->sort('nane', 'ユーザ名', $sort) ?></th>
                            <th><?= $this->Paginator->sort('username', 'アカウント名', $sort) ?></th>
                            <th><?= $this->Paginator->sort('email', 'メールアドレス', $sort) ?></th>
                            <th><?= $this->Paginator->sort('tel', '電話番号', $sort) ?></th>
                            <th><?= $this->Paginator->sort('is_active', 'ログイン可否', $sort) ?></th>
                            <th><?= $this->Paginator->sort('expiration_datetime', 'PW有効期限', $sort) ?></th>
                            <th><?= $this->Paginator->sort('modified', '更新日時', $sort) ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row) { ?>
                        <tr>
                            <td><?= h($row['id']) ?></td>
                            <td><?= h($row['nane']) ?></td>
                            <td><?= h($row['username']) ?></td>
                            <td><?= h($row['email']) ?></td>
                            <td><?= h($row['tel']) ?></td>
                            <td><?= $row['is_active'] ? '有効' : '<span style="color: red">無効</span>' ?></td>
                            <td><?= h($row['expiration_datetime']?->format('Y/m/d H:i:s') ?? '----/--/-- --:--:--') ?></td>
                            <td><?= h($row['modified']?->format('Y/m/d H:i:s') ?? '----/--/-- --:--:--') ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('Detail'), [
                                    'controller' => 'Detail',
                                    'action' => 'index', 
                                    'admin_account_id' => $admin_account_id,
                                    'user_account_id' => $row['id'],
                                ]) ?>
                                <?= $this->Html->link(__('Edit'), [
                                    'controller' => 'Edit',
                                    'action' => 'index', 
                                    'admin_account_id' => $admin_account_id,
                                    'user_account_id' => $row['id'],
                                ]) ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="paginator">
                    <ul class="pagination">
                        <?php
                        
                            $pagenator = [
                                'url' => [
                                    'admin_account_id' => $admin_account_id,
                                    '?' => $this->getRequest()->getQuery(),
                                ],
                            ];
                        ?>
                        <?= $this->Paginator->first('<< ' . __('first'), $pagenator) ?>
                        <?= $this->Paginator->prev('< ' . __('previous'), $pagenator) ?>
                        <?= $this->Paginator->numbers($pagenator) ?>
                        <?= $this->Paginator->next(__('next') . ' >', $pagenator) ?>
                        <?= $this->Paginator->last(__('last') . ' >>', $pagenator) ?>
                    </ul>
                    <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total'), $pagenator) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
