<?php

require_once 'define.php';

require_once 'vendor/autoload.php';

use \App\Core\Router;

//$Router = new Router(__DIR__.'/cache/route.cache');
$Router = new Router();

$Router->get('/', function(){
    echo "index";
});

$Router->get('/users', 'App\Controller\Client@getAll');

$Router->get('/users/{ClientId:[0-9]+}', 'App\Controller\Client@getOne');

$Router->get('/users/{name}/{ClientId:[0-9]+}', function($args, $extras = null){
    print_r($args);
    print_r($extras);
});

$Router->route();

