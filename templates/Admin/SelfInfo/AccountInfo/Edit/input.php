<?php

// debug($messages);

?>
<div class="row">
    <?= $this->element('left_menu') ?>
    <div class="main_menu column-responsive column-80">
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
    <?php if ($messages) { ?>
        <div class="row ">
            <div class="input_messages message error">
                <?= join('<br>', array_map('h', $messages))?>
            </div>
        </div>
    <?php } ?>
        <div class="row">
            <div class="input_form">
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
                        <dt>
                            表示名
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <input 
                                type="text" 
                                name="name" 
                                value="<?= h($input['name']) ?>" 
                                class="<?= h($errors['name'] ?? '') ?>" 
                                placeholder="表示名"
                            >
                        </dd>
                        <dt>
                            メールアドレス
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <input 
                                type="text" 
                                name="email" 
                                value="<?= h($input['email']) ?>" 
                                class="<?= h($errors['email'] ?? '') ?>" 
                                placeholder="メールアドレス"
                            >
                        </dd>
                        <dt>電話番号</dt>
                        <dd>
                            <input 
                                type="text" 
                                name="tel" 
                                value="<?= h($input['tel']) ?>" 
                                class="<?= h($errors['tel'] ?? '') ?>"
                                placeholder="電話番号"
                            >
                        </dd>
                        <dt>
                            確認パスワード
                            <span style="color: #ef5753;">※</span>
                        </dt>
                        <dd>
                            <input 
                                type="password" 
                                name="conf_password" 
                                value="" 
                                class="<?= h($errors['conf_password'] ?? '') ?>"
                                placeholder="確認パスワード"
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
                    <div class="form_buttons">
                        <input type="submit" value="確認画面へ">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>