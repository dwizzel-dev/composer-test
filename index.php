<?php

require_once 'define.php';

require_once 'vendor/autoload.php';

use \App\Core\Router;

if(CACHE_ENABLE) {
    $Router = new Router(CACHE_PATH);
}else{
    $Router = new Router();
    $Router->get('/', [
        '\App\Controller\Index@index',
        'in' => [
            '\App\Middleware\Auth@verify',
            '\App\Middleware\Auth@auth'
        ]
    ]);
    $Router->get('/users', '\App\Controller\Client@getAll');
    $Router->get('/users/{ClientId:[0-9]+}', '\App\Controller\Client@getOne');
}

$Router->route();

