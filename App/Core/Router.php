<?php

namespace App\Core;

use \FastRoute;

class Router{

    private $dispatcher;
    private $uri;
    private $method;
    private $cache;
    private $routes = [];

    public function __construct($cache = null){
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        if(isset($cache)){
            $this->cache = $cache;
        }
    }

    public function __call($name, $arguments){
        array_push($this->routes, [
            "method" => $name,
            "route" => $arguments[0],
            "handler" => $arguments[1],
        ]);
    }

    public function load(FastRoute\RouteCollector $routeCollector){
        foreach ($this->routes as $k=>$v) {
            $routeCollector->addRoute(strtoupper($v["method"]), $v["route"], $v["handler"]);
        }
    }

    public function route(){
        if(isset($this->cache)){
            $this->dispatcher = FastRoute\cachedDispatcher(array($this, 'load'),[
                'cacheFile' => $this->cache,
                'cacheDisabled' => false
            ]);

        }else{
            $this->dispatcher = FastRoute\simpleDispatcher(array($this, 'load'));
        }
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($this->uri, '?')) {
            $this->uri = substr($this->uri, 0, $pos);
        }
        $routeInfo = $this->dispatcher->dispatch($this->method, rawurldecode($this->uri));
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                echo '404';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                echo '405:'.$routeInfo[1];
                break;
            case FastRoute\Dispatcher::FOUND:
                if(is_string($routeInfo[1])) {
                    list($class, $method) = explode('@', $routeInfo[1]);
                    (new $class)->{$method}($routeInfo[2]);
                }else{
                    $routeInfo[1]($routeInfo[2]);
                }
                break;
            default:
                echo 'default';
                break;
        }

    }

}
