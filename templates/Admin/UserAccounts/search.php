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
        <div class="row">
            <div class="column">
                <?= $this->element('breadcrumbs', ['show' => 'user_accounts-search']) ?>
            </div>
        </div>
        <h2>ユーザアカウント管理</h2>
        <?= $this->Flash->render() ?>
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
                            <th>ユーザアカウントID(完全一致)</th>
                            <td>
                                <input 
                                    type="text"
                                    name="user_accounts[id]"
                                    value="<?= h($value['id'] ?? '') ?>"
                                    class="<?= h($error['id'] ?? '') ?>"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>ユーザ名(部分一致)</th>
                            <td>
                                <input 
                                    type="text"
                                    name="user_accounts[name_like]"
                                    value="<?= h($value['name_like'] ?? '') ?>"
                                    class="<?= h($error['name_like'] ?? '') ?>"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>アカウント名(部分一致)</th>
                            <td>
                                <input 
                                    type="text"
                                    name="user_accounts[username_like]"
                                    value="<?= h($value['username_like'] ?? '') ?>"
                                    class="<?= h($error['username_like'] ?? '') ?>"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>メールアドレス(部分一致)</th>
                            <td>
                                <input 
                                    type="text"
                                    name="user_accounts[email_like]"
                                    value="<?= h($value['email_like'] ?? '') ?>"
                                    class="<?= h($error['email_like'] ?? '') ?>"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>電話番号(部分一致)</th>
                            <td>
                                <input 
                                    type="text"
                                    name="user_accounts[tel_like]"
                                    value="<?= h($value['tel_like'] ?? '') ?>"
                                    class="<?= h($error['tel_like'] ?? '') ?>"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>ログイン可否</th>
                            <td>
                                <div class="multiple_checkbox">
                                    <label>
                                        <input type="checkbox" class="all_check is_active">
                                        全て
                                    </label>
                                <?php foreach (['0' => 'ログイン不可', '1' => 'ログイン可'] as $val => $lavel) { ?>
                                    <label>
                                        <input 
                                            type="checkbox"
                                            name="user_accounts[is_active][]"
                                            value="<?= h($val) ?>"
                                            class="<?= h($error['is_active'] ?? '') ?>"
                                            <?= in_array((string) $val, $value['is_active'] ?? [], true) ? 'checked' : '' ?>
                                        > <?= h($lavel) ?>
                                    </label>
                                <?php } ?>
                                    <script>(function($) {

                                        var elAllCheck = '.all_check.is_active';
                                        var elCheck = '[name="user_accounts[is_active][]"]';

                                        $(elAllCheck).prop('checked', $(elCheck).length === $(elCheck).filter(':checked').length);

                                        $('body').on('click', elAllCheck, function() {

                                            $(elCheck).prop('checked', $(this).prop('checked'));
                                        }).on('click', elCheck, function() {

                                            $(elAllCheck).prop('checked', $(elCheck).length === $(elCheck).filter(':checked').length);
                                        });

                                    })(jQuery);</script>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>仮PW設定</th>
                            <td>
                                <div class="multiple_checkbox">
                                    <label>
                                        <input type="checkbox" class="all_check is_tmp_password">
                                        全て
                                    </label>
                                <?php foreach (['0' => '正PW', '1' => '仮PW'] as $val => $lavel) { ?>
                                    <label>
                                        <input 
                                            type="checkbox"
                                            name="user_accounts[is_tmp_password][]"
                                            value="<?= h($val) ?>"
                                            class="<?= h($error['is_tmp_password'] ?? '') ?>"
                                            <?= in_array((string) $val, $value['is_tmp_password'] ?? [], true) ? 'checked' : '' ?>
                                        > <?= h($lavel) ?>
                                    </label>
                                <?php } ?>
                                    <script>(function($) {

                                        var elAllCheck = '.all_check.is_tmp_password';
                                        var elCheck = '[name="user_accounts[is_tmp_password][]"]';

                                        $(elAllCheck).prop('checked', $(elCheck).length === $(elCheck).filter(':checked').length);

                                        $('body').on('click', elAllCheck, function() {

                                            $(elCheck).prop('checked', $(this).prop('checked'));
                                        }).on('click', elCheck, function() {

                                            $(elAllCheck).prop('checked', $(elCheck).length === $(elCheck).filter(':checked').length);
                                        });

                                    })(jQuery);</script>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>PW有効期限</th>
                            <td>
                                <div class="input_from_to">
                                    <input 
                                        type="datetime-local"
                                        name="user_accounts[expiration_datetime_from]"
                                        value="<?= h($value['expiration_datetime_from'] ?? '') ?>"
                                        class="<?= h($error['expiration_datetime_from'] ?? '') ?>"
                                        step="1"
                                    >
                                    〜
                                    <input 
                                        type="datetime-local"
                                        name="user_accounts[expiration_datetime_to]"
                                        value="<?= h($value['expiration_datetime_to'] ?? '') ?>"
                                        class="<?= h($error['expiration_datetime_to'] ?? '') ?>"
                                        step="1"
                                    >
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>作成日時</th>
                            <td>
                                <div class="input_from_to">
                                    <input 
                                        type="datetime-local"
                                        name="user_accounts[created_from]"
                                        value="<?= h($value['created_from'] ?? '') ?>"
                                        class="<?= h($error['created_from'] ?? '') ?>"
                                        step="1"
                                    >
                                    〜
                                    <input 
                                        type="datetime-local"
                                        name="user_accounts[created_to]"
                                        value="<?= h($value['created_to'] ?? '') ?>"
                                        class="<?= h($error['created_to'] ?? '') ?>"
                                        step="1"
                                    >
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>更新日時</th>
                            <td>
                                <div class="input_from_to">
                                    <input 
                                        type="datetime-local"
                                        name="user_accounts[modified_from]"
                                        value="<?= h($value['modified_from'] ?? '') ?>"
                                        class="<?= h($error['modified_from'] ?? '') ?>"
                                        step="1"
                                    >
                                    〜
                                    <input 
                                        type="datetime-local"
                                        name="user_accounts[modified_to]"
                                        value="<?= h($value['modified_to'] ?? '') ?>"
                                        class="<?= h($error['modified_to'] ?? '') ?>"
                                        step="1"
                                    >
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>キーワード</th>
                            <td>
                                <input 
                                    type="text"
                                    name="user_accounts[keyword]"
                                    value="<?= h($value['keyword'] ?? '') ?>"
                                    class="<?= h($error['keyword'] ?? '') ?>"
                                >
                            </td>
                        </tr>
                    </table>
                    <div class="form_buttons">
                        <input type="submit" class="search_button" value="検索">
                    </div>
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
                            <th><?= $this->Paginator->sort('name', 'ユーザ名', $sort) ?></th>
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
                            <td><?= h($row['name']) ?></td>
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
