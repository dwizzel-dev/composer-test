<?php

require_once 'vendor/autoload.php';

use \App\Core\Router;

//$Router = new Router(__DIR__.'/cache/route.cache');
$Router = new Router();

$Router->get('/users', 'App\Core\Client@getAll');

$Router->get('/users/{id:[0-9]+}', 'App\Core\Client@getOne');

$Router->get('/users/{name}/{id:[0-9]+}', function($args){
    echo "id[${args['id']}]=${args['name']}";
});

$Router->route();

