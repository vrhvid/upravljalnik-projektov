<?php
    require 'vendor/autoload.php';   
    require 'config.php';

    $connectionString = 'mongodb+srv://'.$CFG->mdbuser.':'.$CFG->mdbpassword.'@'.$CFG->mdbserver.'/?retryWrites=true&w=majority&appName=Cluster0';
    $client = new MongoDB\Client($connectionString);

    $database = $CFG->mdbdatabase;
    $collection = $client->$database->users;

    $data = json_decode(file_get_contents("php://input"), true);

    if(isset($data["name"], $data["surname"], $data["email"], $data["password"], $data["roles"])){
        try{
            $insertOne = $collection->insertOne([
            'name' => $data["name"],
            'surname' => $data["surname"],
            'email' => $data["email"],
            'password' => $data["password"],
            'roles' => [$data["roles"]],
        ]);
        } catch (Exception $e){
            http_response_code(500);
        }
        http_response_code(200);
    }
?>