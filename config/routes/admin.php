<?php


use Cake\Routing\RouteBuilder;


// 管理画面用ルーティング
$builder->prefix('Admin', ['path' => '/ad'], static function (RouteBuilder $builder) {

    $builder->get('/', ['controller' => 'Login', 'action' => 'index']);
    $builder->post('/', ['controller' => 'Login', 'action' => 'indexPost']);
    
    $builder->get('/error/:message_key', ['controller' => 'Auth', 'action' => 'index']);
    
    $builder->scope('/:admin_account_id', function (RouteBuilder $builder) {
        
        $builder->registerMiddleware('adminAuth', new App\Middleware\Auth\AdminAuthMiddleware())->applyMiddleware('adminAuth');
        
        $builder->get('/logout/', ['controller' => 'Top', 'action' => 'index']);
        // ユーザアカウント管理
        $builder->prefix('UserAccounts', ['path' => '/user_accounts'], static function (RouteBuilder $builder) {
            // ユーザアカウント検索
            $builder->get('/init', ['controller' => 'Search', 'action' => 'init']);
            $builder->get('/', ['controller' => 'Search', 'action' => 'index']);
            // ユーザアカウント詳細
            $builder->get('/detail/:user_account_id', ['controller' => 'Detail', 'action' => 'index']);
            // ユーザアカウント作成
            $builder->get('/create', ['controller' => 'Create', 'action' => 'index']);
            $builder->get('/create/input/:tmp_id', ['controller' => 'Create', 'action' => 'input']);
            $builder->post('/create/input/:tmp_id', ['controller' => 'Create', 'action' => 'inputPost']);
            $builder->get('/create/conf/:tmp_id', ['controller' => 'Create', 'action' => 'conf']);
            $builder->post('/create/conf/:tmp_id', ['controller' => 'Create', 'action' => 'confPost']);
            // ユーザアカウント更新
            $builder->get('/edit/:user_account_id', ['controller' => 'Edit', 'action' => 'index']);
            $builder->get('/edit/input/:tmp_id', ['controller' => 'Edit', 'action' => 'input']);
            $builder->post('/edit/input/:tmp_id', ['controller' => 'Edit', 'action' => 'inputPost']);
            $builder->get('/edit/conf/:tmp_id', ['controller' => 'Edit', 'action' => 'conf']);
            $builder->post('/edit/conf/:tmp_id', ['controller' => 'Edit', 'action' => 'confPost']);
            // ユーザアカウント一括処理
            $builder->prefix('Bulk', ['path' => '/bulk'], static function (RouteBuilder $builder) {
                // 一括登録
                $builder->get('/create', ['controller' => 'Create', 'action' => 'index']);
                $builder->get('/create/input/:tmp_id', ['controller' => 'Create', 'action' => 'input']);
                $builder->post('/create/input/:tmp_id', ['controller' => 'Create', 'action' => 'inputPost']);
                $builder->get('/create/conf/:tmp_id', ['controller' => 'Create', 'action' => 'conf']);
                $builder->post('/create/conf/:tmp_id', ['controller' => 'Create', 'action' => 'confPost']);
                // 一括更新
                $builder->post('/edit', ['controller' => 'Edit', 'action' => 'index']);
                $builder->get('/edit/input/:tmp_id', ['controller' => 'Edit', 'action' => 'input']);
                $builder->post('/edit/input/:tmp_id', ['controller' => 'Edit', 'action' => 'inputPost']);
                $builder->get('/edit/conf/:tmp_id', ['controller' => 'Edit', 'action' => 'conf']);
                $builder->post('/edit/conf/:tmp_id', ['controller' => 'Edit', 'action' => 'confPost']);
            });
            // ユーザアカウントCSV処理 
            $builder->prefix('Csv', ['path' => '/csv'], static function (RouteBuilder $builder) {
                // インポート
                $builder->get('/import', ['controller' => 'Import', 'action' => 'index']);
                $builder->get('/import/input/:tmp_id', ['controller' => 'Import', 'action' => 'input']);
                $builder->post('/import/input/:tmp_id', ['controller' => 'Import', 'action' => 'inputPost']);
                $builder->get('/import/conf/:tmp_id', ['controller' => 'Create', 'action' => 'conf']);
                $builder->post('/import/conf/:tmp_id', ['controller' => 'Create', 'action' => 'confPost']);
                $builder->get('/import/comp/:tmp_id', ['controller' => 'Create', 'action' => 'comp']);
                // エクスポート
                $builder->post('/export', ['controller' => 'Export', 'action' => 'index']);
            });
        });
    });
    


    // $builder->fallbacks();
});