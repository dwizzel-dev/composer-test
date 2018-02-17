<?php

namespace App\Controller;

use App\Model\ClientModel;

class Client{

    private static $clientModel;

    public function __construct(){
       self::$clientModel = new ClientModel;
    }

    public static function getAll(){
        $result = self::$clientModel->getAll();
        if($result->getRowCount()) {
            foreach ($result as $row) {
                echo "row[" . $row->ClientId . ']:' . $row->name.PHP_EOL;
            }
        }
    }

    public static function getOne($args, $extras = null){
        $row = self::$clientModel->getOne($args["ClientId"]);
        if(!$row){
            echo "client [${args["ClientId"]}] dont exist".PHP_EOL;
        }else{
            echo "row[" . $row->ClientId . ']:' . $row->name . PHP_EOL;
        }
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