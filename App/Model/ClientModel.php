<?php

namespace App\Model;

use \Nette\Database\Connection;

class ClientModel{

    private $database;

    public function __construct(){
        $this->database = new Connection(DNS, USER, PSW);
    }

    public function getAll(){
        return $this->database->query("SELECT * FROM Clients ORDER BY", [
            'name' => true,
            'ClientId' => false,
        ]);
    }

    public function getOne($id){
        return $this->database->fetch("SELECT * FROM Clients WHERE ClientId =  ? ", $id);
    }

    public function updateOne($args, $extras = null){
        echo "update one clients: ${args['id']}";
    }

    public function deleteOne($args, $extras = null){
        echo "delete one clients: ${args['id']}";
    }

    public function addOne(){
        echo "add one clients";
    }

}