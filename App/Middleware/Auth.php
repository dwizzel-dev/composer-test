<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;

class Auth extends Middleware {

    public function __construct(){

    }

    public function auth(Request $request){
        echo "<pre>".__METHOD__."</pre>";
        $request->{'auth'} = true;
        var_dump($request);
        $this->next($request);
    }

    public function verify(Request $request){
        echo "<pre>".__METHOD__."</pre>";
        $request->{'verify'} = true;
        var_dump($request);
        $this->next($request);
    }



}