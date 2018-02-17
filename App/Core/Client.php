<?php

namespace App\Core;

class Client{

    public function __construct(){
    }

    public static function getAll(){
        echo "get all clients";
    }

    public static function getOne($args){
        echo "get one clients: ${args['id']}";
    }

    public static function updateOne($args){
        echo "update one clients: ${args['id']}";
    }

    public static function deleteOne($args){
        echo "delete one clients: ${args['id']}";
    }

    public static function addOne(){
        echo "add one clients";
    }

}