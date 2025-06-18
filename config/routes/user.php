<?php


use Cake\Routing\RouteBuilder;


// ユーザ画面用ルーティング
$builder->prefix('User', ['path' => '/us/:user_account_id'], static function (RouteBuilder $builder) {
    // ユーザアカウント
    $builder->prefix('SelfAccount', ['path' => '/self_account'], static function (RouteBuilder $builder) {
        // ユーザアカウント情報
        $builder->get('/detail', ['controller' => 'Detail', 'action' => 'index']);
        // ユーザアカウント更新
        $builder->get('/edit', ['controller' => 'Edit', 'action' => 'index']);
        $builder->get('/edit/input/:input_id', ['controller' => 'Edit', 'action' => 'input']);
        $builder->post('/edit/input/:input_id', ['controller' => 'Edit', 'action' => 'inputPost']);
        $builder->get('/edit/conf/:input_id', ['controller' => 'Edit', 'action' => 'conf']);
        $builder->post('/edit/conf/:input_id', ['controller' => 'Edit', 'action' => 'confPost']);
    });
    // ユーザパスワード
    $builder->prefix('SelfPassword', ['path' => '/self_account'], static function (RouteBuilder $builder) {
        // ユーザパスワード更新
        $builder->get('/edit', ['controller' => 'Edit', 'action' => 'index']);
        $builder->get('/edit/input/:input_id', ['controller' => 'Edit', 'action' => 'input']);
        $builder->post('/edit/input/:input_id', ['controller' => 'Edit', 'action' => 'inputPost']);
        $builder->get('/edit/conf/:input_id', ['controller' => 'Edit', 'action' => 'conf']);
        $builder->post('/edit/conf/:input_id', ['controller' => 'Edit', 'action' => 'confPost']);
    });
    

    // $builder->fallbacks();
});

    

