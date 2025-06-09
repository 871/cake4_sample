<?php

// debug($messages);

?>
<div class="row">
    <?= $this->element('left_menu') ?>
    <div class="column-responsive column-80">
        <div class="row">
            <div class="column">
                <?= $this->element('top_menu', ['show' => 'self_info_detail-password_edit']) ?>
            </div>
        </div>
        <h2>パスワード情報更新</h2>
        <?= $this->Flash->render() ?>
        <div class="row">
            <div class="column">
                <?= $this->element('breadcrumbs', ['show' => 'self_info_detail-password_edit']) ?>
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
                <form method="post">
                    <input type="hidden" name="_csrfToken" value="<?= h($this->request->getAttribute('csrfToken')) ?>" />
                    <dl>
                        <dt>管理者ID</dt>
                        <dd>
                            <?= h($input['id'] ?? '---') ?>
                        </dd>
                        <dt>ログインアカウント</dt>
                        <dd>
                            <?= h($input['username'] ?? '---') ?>
                        </dd>
                        <dt>パスワードの有効期限</dt>
                        <dd>
                            <?= h($input['show_expiration_datetime'] ?? '----/--/-- --:--:--') ?>
                        </dd>
                        <dt>
                            新パスワード
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <input 
                                type="password" 
                                name="new_password" 
                                value="" 
                                class="<?= h($errors['new_password'] ?? '') ?>" 
                                placeholder="新パスワード"
                            >
                        </dd>
                        <dt>
                            新パスワード(確認)
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <input 
                                type="password" 
                                name="new_password_conf" 
                                value="" 
                                class="<?= h($errors['new_password_conf'] ?? '') ?>" 
                                placeholder="新パスワード(確認)"
                            >
                        </dd>
                        <dt>
                            現在のパスワード
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <input 
                                type="password" 
                                name="old_password" 
                                value="" 
                                class="<?= h($errors['old_password'] ?? '') ?>"
                                placeholder="現在のパスワード"
                            >
                        </dd>
                        <dt>作成日時</dt>
                        <dd>
                            <?= h($input['show_created'] ?? '----/--/-- --:--:--') ?>
                        </dd>
                        <dt>更新日時</dt>
                        <dd>
                            <?= h($input['show_modified'] ?? '----/--/-- --:--:--') ?>
                        </dd>
                    </dl>
                    <input type="submit" value="確認画面へ">
                </form>
            </div>
        </div>
    </div>
</div>