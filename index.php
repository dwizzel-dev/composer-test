<?php

require 'vendor/autoload.php';

use \Slim\Slim;

$app = new Slim();

$app->get('/', function () {
    echo 'index.html';
});

$app->get('/hello/:name', function ($name) {
    echo "hello $name!";
});

$app->run();

