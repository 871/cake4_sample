<?php


use Cake\Routing\RouteBuilder;


$builder->scope('/', function (RouteBuilder $builder) {
    
    $builder->get('/error', ['controller' => 'Error', 'action' => 'index']);
    $builder->get('/error/:message_id', ['controller' => 'Error', 'action' => 'index']);

});

    

