<?php


use Cake\Routing\RouteBuilder;


$builder->scope('/', function (RouteBuilder $builder) {
    // 管理画面用ルーティング
    $builder->prefix('', function (RouteBuilder $builder) {

        
        $builder->fallbacks();
    });
});

    

