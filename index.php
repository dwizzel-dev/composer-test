<?php

require_once 'vendor/autoload.php';

//$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
$dispatcher = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $r) {
    $r->get('/users', '\App\Core\Client@getAll');
    $r->get('/users/{id:\d+}', '\App\Core\Client@getOne');
    $r->addRoute('PUT', '/users', '\App\Core\Client@addOne');
    $r->addRoute('POST', '/users/{id:\d+}', '\App\Core\Client@updateOne');
    $r->addRoute('DELETE', '/users/{id:\d+}', '\App\Core\Client@deleteOne');
},[
    'cacheFile' => __DIR__.'/cache/route.cache',
    'cacheDisabled' => true
]);

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$routeInfo = $dispatcher->dispatch($httpMethod, rawurldecode($uri));

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405';
        break;
    case FastRoute\Dispatcher::FOUND:
        list($class, $method) = explode('@', $routeInfo[1]);
        (new $class)->{$method}($routeInfo[2]);
        break;
    default:
        echo 'default';
        break;
}

