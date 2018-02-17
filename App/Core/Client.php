<?php

namespace App\Core;

class Client{

    public function __construct(){
    }

    public static function getAll(){
        echo "get all clients";
    }

    public static function getOne($args, $extras = null){
        echo "get one clients: ${args['id']}";
    }

    public static function updateOne($args, $extras = null){
        echo "update one clients: ${args['id']}";
    }

    public static function deleteOne($args, $extras = null){
        echo "delete one clients: ${args['id']}";
    }

    public static function addOne(){
        echo "add one clients";
    }

}