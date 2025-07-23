<?php

    $admin_account_id = $this->getRequest()->getParam('admin_account_id');

    $value = array_filter($this->getRequest()->getQuery('user_accounts', []), fn($v) => (string) $v !== '');
    $error = $errors['user_accounts'] ?? [];
                        
    $pagenator = [
        'url' => [
            'admin_account_id' => $admin_account_id,
            '?' => $this->getRequest()->getQuery(),
        ],
    ];
?>
<div class="row">
    <?= $this->element('left_menu') ?>
    <div class="main_menu column-responsive column-80">
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
            <div class="search_form <?= $value === [] ? 'close' : '' ?>">
                <a href="#" class="open_button">検索フォーム表示▽</a>
                <a href="#" class="close_button">検索フォーム非表示▲</a>
                <script>(function($) {

                    $('body').on('click', '.search_form .open_button', function() {

                        $(this).closest('.search_form').removeClass('close');
                        return false;
                    }).on('click', '.search_form .close_button', function() {

                        $(this).closest('.search_form').addClass('close');
                        return false;
                    });
                })(jQuery);</script>
                <form method="get">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="limit" value="<?= h($this->getRequest()->getQuery('limit', 20)) ?>">
                    <table>
                        <tr>
                            <th style="width: 25%;">ユーザアカウントID(完全一致)</th>
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
            <?= $this->element('pagenator', [
                'url' => [
                    'admin_account_id' => $admin_account_id,
                    '?' => $this->getRequest()->getQuery(),
                ],
            ]) ?>
            <?= $this->element('page_counter') ?>
            <?= $this->element('page_limit', [
                'url' => [
                    'admin_account_id' => $admin_account_id,
                    '?' => $this->getRequest()->getQuery(),
                ],
            ]) ?>
        </div>
        <div class="row">
            <div class="page_results">
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
                            <th style="width: 85px;" data-view_switch="ID" data-view_default="1"><?= $this->Paginator->sort('UserAccounts.id', 'ID', $sort) ?></th>
                            <th style="width: 140px;" data-view_switch="ユーザ名" data-view_default="1"><?= $this->Paginator->sort('UserAccounts.name', 'ユーザ名', $sort) ?></th>
                            <th style="width: 140px;" data-view_switch="アカウント名" data-view_default="1"><?= $this->Paginator->sort('UserAccounts.username', 'アカウント名', $sort) ?></th>
                            <th style="width: 160px;" data-view_switch="メールアドレス" data-view_default="1"><?= $this->Paginator->sort('UserAccounts.email', 'メールアドレス', $sort) ?></th>
                            <th style="width: 140px;" data-view_switch="電話番号" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.tel', '電話番号', $sort) ?></th>
                            <th style="width: 95px;" data-view_switch="ログイン" data-view_default="1"><?= $this->Paginator->sort('UserAccounts.is_active', 'ログイン', $sort) ?></th>
                            <th style="width: 190px;" data-view_switch="PW有効期限" data-view_default="1"><?= $this->Paginator->sort('UserAccounts.expiration_datetime', 'PW有効期限', $sort) ?></th>
                            <th style="width: 95px;" data-view_switch="PW種別" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.is_tmp_password', 'PW種別', $sort) ?></th>
                            <th style="width: 300px;" data-view_switch="備考" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.remarks', '備考', $sort) ?></th>
                            <th style="width: 190px;" data-view_switch="作成日時" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.created', '作成日時', $sort) ?></th>
                            <th style="width: 190px;" data-view_switch="更新日時" data-view_default="1"><?= $this->Paginator->sort('UserAccounts.modified', '更新日時', $sort) ?></th>
                            <th style="width: 100px;" data-view_switch="作成者ID" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.created_account_id', '作成者ID', $sort) ?></th>
                            <th style="width: 100px;" data-view_switch="更新者ID" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.modified_account_id', '更新者ID', $sort) ?></th>
                            <th style="width: 190px;" data-view_switch="作成者IP" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.created_ip', '作成者IP', $sort) ?></th>
                            <th style="width: 190px;" data-view_switch="更新者IP" data-view_default="0"><?= $this->Paginator->sort('UserAccounts.modified_ip', '更新者IP', $sort) ?></th>
                            <th style="width: 380px;" class="actions "><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rows as $row) { ?>
                        <tr>
                            <td class="primary_id" data-view_target="ID">
                                <?= h($row['id']) ?>
                            </td>
                            <td class="text" data-view_target="ユーザ名">
                                <input type="text" value="<?= h($row['name']) ?>" readonly>
                            </td>
                            <td class="text" data-view_target="アカウント名">
                                <input type="text" value="<?= h($row['username']) ?>" readonly>
                            </td>
                            <td class="text" data-view_target="メールアドレス">
                                <input type="text" value="<?= h($row['email']) ?>" readonly>
                            </td>
                            <td class="text" data-view_target="電話番号">
                                <input type="text" value="<?= h($row['tel']) ?>" readonly>
                            </td>
                            <td class="status" data-view_target="ログイン">
                                <?= $row['is_active'] ? '有効' : '<span style="color: red">無効</span>' ?>
                            </td>
                            <td class="datetime" data-view_target="PW有効期限">
                                <?= h($row['expiration_datetime']?->format('Y-m-d H:i:s') ?? '') ?>
                            </td>
                            <td class="status" data-view_target="PW種別">
                                <?= $row['is_tmp_password'] ? '正' : '<span style="color: red">仮</span>' ?>
                            </td>
                            <td class="text" data-view_target="備考">
                                <input type="text" value="<?= h($this->Text->truncate(preg_replace('/\s+/', ' ', $row['remarks']), 100)) ?>" readonly>
                            </td>
                            <td class="datetime" data-view_target="作成日時">
                                <?= h($row['created']?->format('Y-m-d H:i:s') ?? '') ?>
                            </td>
                            <td class="datetime" data-view_target="更新日時">
                                <?= h($row['modified']?->format('Y-m-d H:i:s') ?? '') ?>
                            </td>
                            <td class="primary_id" data-view_target="作成者ID">
                                <?= h($row['created_account_id']) ?>
                            </td>
                            <td class="primary_id" data-view_target="更新者ID">
                                <?= h($row['modified_account_id']) ?>
                            </td>
                            <td class="text" data-view_target="作成者IP">
                                <input type="text" value="<?= h($row['created_ip']) ?>" readonly>
                            </td>
                            <td class="text" data-view_target="更新者IP">
                                <input type="text" value="<?= h($row['modified_ip']) ?>" readonly>
                            </td>
                            <td class="actions">
                                <a 
                                    class="btn green_line" 
                                    href="<?= $this->Url->build([
                                        'controller' => 'Detail',
                                        'action' => 'index', 
                                        'admin_account_id' => $admin_account_id,
                                        'user_account_id' => $row['id'],
                                    ]); ?>"
                                >詳細</a>
                                <a 
                                    class="btn green_fill" 
                                    href="<?= $this->Url->build([
                                        'controller' => 'Edit',
                                        'action' => 'index', 
                                        'admin_account_id' => $admin_account_id,
                                        'user_account_id' => $row['id'],
                                    ]); ?>"
                                >更新</a>
                                <a 
                                    class="btn green_fill" 
                                    href="#"
                                >複製</a>
                                <a 
                                    class="btn red_fill" 
                                    href="#"
                                >代理ログイン</a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <?= $this->element('pagenator', [
                'url' => [
                    'admin_account_id' => $admin_account_id,
                    '?' => $this->getRequest()->getQuery(),
                ],
            ]) ?>
            <?= $this->element('page_counter') ?>
            <?= $this->element('page_limit', [
                'url' => [
                    'admin_account_id' => $admin_account_id,
                    '?' => $this->getRequest()->getQuery(),
                ],
            ]) ?>
        </div>
        <div class="row">
            
            <div class="view_colmun_setting close">
                <a href="#" class="open_button">表示列設定▽</a>
                <a href="#" class="close_button">表示列設定▲</a>
                <div class="multiple_checkbox">
                    <label>
                        <input type="checkbox" class="all_check view_colmun">
                        全て
                    </label>
        
                </div>
            </div>
            <script>(function($) {
    
                $('body').on('click', '.view_colmun_setting .open_button', function() {

                    $(this).closest('.view_colmun_setting').removeClass('close');
                    return false;
                }).on('click', '.view_colmun_setting .close_button', function() {

                    $(this).closest('.view_colmun_setting').addClass('close');
                    return false;
                });
            })(jQuery);</script>
        </div>
    </div>
</div>
