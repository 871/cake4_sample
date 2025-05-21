<?php


use Cake\Routing\RouteBuilder;


$builder->scope('/user', function (RouteBuilder $builder) {
    // ユーザ画面用ルーティング
    $builder->prefix('User', function (RouteBuilder $builder) {

        
        $builder->fallbacks();
    });
});

    

