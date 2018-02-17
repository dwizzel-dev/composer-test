<?php

namespace App\Core;

use \FastRoute;

class Router{

    private static $inst;
    private $allowed = ['get','post','put','delete','head'];
    private $cache;
    private $routes = [];

    public function __construct($cache = null){
        if(isset($cache)){
            $this->cache = $cache;
        }
    }

    public static function self(){
        if(self::$inst === null){
            self::$inst = new Router;
        }
        return self::$inst;
    }

    public function __call($name, $arguments){
        if(in_array($name, $this->allowed)) {
            array_push($this->routes, [
                "method" => $name,
                "route" => $arguments[0],
                "handler" => $arguments[1],
            ]);
        }
    }

    public function load(FastRoute\RouteCollector $routeCollector){
        foreach ($this->routes as $k=>$v) {
            $routeCollector->addRoute(strtoupper($v["method"]), $v["route"], $v["handler"]);
        }
    }

    public function extras($query, &$arr){
        foreach(explode("&", $query) as $v) {
            if ($v != "") {
                list($key, $val) = explode("=", $v);
                if ($val != "") {
                    $arr[$key] = $val;
                }
            }
        }
    }

    public function callClassMethod($params){
        try {
            if (substr_count($params[1], "@")) {
                list($class, $method) = explode('@', $params[1]);
            } else {
                throw new \Exception("invalid class::method call");
            }
            if (isset($class) && isset($method) && method_exists($class, $method)) {
                (new $class)->{$method}($params[2], $params[3]);
            } else {
                throw new \Exception("class::method dont exist");
            }
        }catch (\Exception $exception){
            var_dump($params);
            var_dump($exception);
        }
    }

    public function route()
    {

        if (isset($this->cache)) {
            $dispatcher = FastRoute\cachedDispatcher(array($this, 'load'), [
                'cacheFile' => $this->cache,
                'cacheDisabled' => false
            ]);
        } else {
            $dispatcher = FastRoute\simpleDispatcher(array($this, 'load'));
        }
        $url = parse_url($_SERVER['REQUEST_URI']);
        $reqMethod = $_SERVER['REQUEST_METHOD'];
        $routeInfo = $dispatcher->dispatch($reqMethod, rawurldecode($url["path"]));
        $routeInfo[3] = [];
        if (isset($url["query"])) {
            $this->extras($url["query"], $routeInfo[3]);
        }
        print_r($routeInfo);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                echo '404';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                echo '405: '.$routeInfo[1];
                break;
            case FastRoute\Dispatcher::FOUND:
                (is_string($routeInfo[1]))? $this->callClassMethod($routeInfo) : $routeInfo[1]($routeInfo[2], $routeInfo[3]);
                break;
            default:
                echo '404';
                break;
        }

    }

}
