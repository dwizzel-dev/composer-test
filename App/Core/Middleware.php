<?php

namespace App\Core;

class Middleware{

    protected function next(Request $request){
        echo "<pre>".__METHOD__."</pre>";
        return $request;
    }

}