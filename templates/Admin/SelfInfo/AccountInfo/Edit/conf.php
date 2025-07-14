<?php


    $admin_account_id = $this->getRequest()->getParam('admin_account_id');
    $input_id = $this->getRequest()->getParam('input_id');
?>
<div class="row">
    <?= $this->element('left_menu') ?>
    <div class="column-responsive column-80">
        <div class="row">
            <div class="column">
                <?= $this->element('top_menu', ['show' => 'self_info_detail-account_info_edit']) ?>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <?= $this->element('breadcrumbs', ['show' => 'self_info_detail-account_info_edit']) ?>
            </div>
        </div>
        <h2>アカウント情報更新</h2>
        <?= $this->Flash->render() ?>
        <div class="row">
            <div class="column">
                <form method="post">
                    <input type="hidden" name="_csrfToken" value="<?= h($this->request->getAttribute('csrfToken')) ?>" />
                    <dl>
                        <dt>管理者ID</dt>
                        <dd>
                            <?= h($input['id'] ? : '---') ?>
                        </dd>
                        <dt>ログインアカウント</dt>
                        <dd>
                            <?= h($input['username'] ? : '---') ?>
                        </dd>
                        <dt>
                            表示名
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <?= h($input['name'] ? : '---') ?>
                        </dd>
                        <dt>
                            メールアドレス
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <?= h($input['email'] ? : '---') ?>
                        </dd>
                        <dt>電話番号</dt>
                        <dd>
                            <?= h($input['tel'] ? : '---') ?>
                        </dd>
                        <dt>
                            確認パスワード
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            (非表示)
                        </dd>
                        <dt>作成日時</dt>
                        <dd>
                            <?= h($input['show_created'] ? : '----/--/-- --:--:--') ?>
                        </dd>
                        <dt>更新日時</dt>
                        <dd>
                            <?= h($input['show_modified'] ? : '----/--/-- --:--:--') ?>
                        </dd>
                    </dl>
                    <input type="submit" value="更新">
                    <a href="<?= $this->Url->build([
                        'admin_account_id' => $admin_account_id,
                        'input_id' => $input_id,
                        'action' => 'input',
                        '?' => $this->getRequest()->getQuery(),
                    ]) ?>" class="button">戻る</a>
                </form>
            </div>
        </div>
    </div>
</div>