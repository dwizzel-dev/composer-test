<?php

namespace App\Core;


use \FastRoute;

class Router{

    private static $inst;
    private $allowed = ['get','post','put','delete','head','patch'];
    private $controllerNamespace = '\\App\\Controller\\';
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
        echo "<pre>".__METHOD__."</pre>";
        if(in_array($name, $this->allowed)) {
            array_push($this->routes, [
                "method" => $name,
                "route" => $arguments[0],
                "handler" => $arguments[1]
            ]);
        }
    }

    public function load(FastRoute\RouteCollector $routeCollector){
        echo "<pre>".__METHOD__."</pre>";
        foreach ($this->routes as $k=>$v) {
            $routeCollector->addRoute(strtoupper($v["method"]), $v["route"], $v["handler"]);
        }
    }

    public function callClassMethod($handler, $args = null){
        echo "<pre>".__METHOD__."</pre>";
        try {
            if (substr_count($handler, "@")) {
                list($class, $method) = explode('@', $handler);
            } else {
                throw new \Exception(__METHOD__." invalid class::method call");
            }
            if (isset($class) && isset($method)){
                if(method_exists($class, $method)) {
                    (new $class)->{$method}($args);
                }else{
                    throw new \Exception(__METHOD__." class::method dont exist");
                }
            } else {
                throw new \Exception(__METHOD__." class::method not set");
            }
        }catch (\Exception $exception){
            var_dump($handler);
            var_dump($args);
        }
    }

    public function callMiddleware($handler, Request $request){
        echo "<pre>".__METHOD__."</pre>";
        try {
            if (substr_count($handler, "@")) {
                list($class, $method) = explode('@', $handler);
            } else {
                throw new \Exception(__METHOD__." invalid class::method call");
            }
            if (isset($class) && isset($method)){
                if(method_exists($class, $method)) {
                    $request = (new $class)->{$method}($request);
                }else{
                    throw new \Exception(__METHOD__." class::method dont exist");
                }
            } else {
                throw new \Exception(__METHOD__." class::method not set");
            }
        }catch (\Exception $exception){
            var_dump($handler);
            var_dump($request);
        }
        return $request;
    }

    public function callOuterware($handler, Response $response = null){
        echo "<pre>".__METHOD__."</pre>";

    }

    public function handle($handler, $args){
        echo "<pre>".__METHOD__."</pre>";
        $in = false;
        $out = false;
        if(is_array($handler)){
            if(isset($handler["in"])){
                $in = $handler["in"];
            }
            if(isset($handler["out"])){
                $out = $handler["out"];
            }
            $handler = $handler[0];
        }
        $request = new Request;
        $request->data();
        if(is_array($args)){
            foreach($args as $k=>$v){
                $request->{$k} = $v;
            }
        }
        if($in){
            foreach($in as $v){
                $this->callMiddleware($v, $request);
            }
        }
        $this->callClassMethod($handler, $args);
        if($out){

        }
    }

    public function route(){
        echo "<pre>".__METHOD__."</pre>";

        if (isset($this->cache)) {
            $dispatcher = FastRoute\cachedDispatcher(array($this, 'load'), [
                'cacheFile' => $this->cache,
                'cacheDisabled' => false
            ]);
        } else {
            $dispatcher = FastRoute\simpleDispatcher(array($this, 'load'));
        }
        $url = parse_url($_SERVER['REQUEST_URI']);
        $reqUri = $url["path"];
        $reqMethod = $_SERVER['REQUEST_METHOD'];
        $routeInfo = $dispatcher->dispatch($reqMethod, rawurldecode($reqUri));
        var_dump($routeInfo);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                echo '404';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                echo '405: '.$routeInfo[1];
                break;
            case FastRoute\Dispatcher::FOUND:
                $this->handle($routeInfo[1], $routeInfo[2]);
                //(is_string($routeInfo[1]))? $this->callClassMethod($routeInfo) : $routeInfo[1]($routeInfo[2]);

                break;
            default:
                echo '404';
                break;
        }

    }

}
