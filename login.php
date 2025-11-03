<?php
    require 'vendor/autoload.php';   
    require 'config.php';

    $connectionString = 'mongodb+srv://'.$CFG->mdbuser.':'.$CFG->mdbpassword.'@'.$CFG->mdbserver.'/?retryWrites=true&w=majority&appName=Cluster0';
    $client = new MongoDB\Client($connectionString);

    $database = $CFG->mdbdatabase;
    $collection = $client->$database->users;

    $data = json_decode(file_get_contents("php://input"), true);
    
    if(isset($data["email"],$data["password"])){
        
        $readResult = $collection->findOne(
            [
                'email' => $data["email"]
            ],
            []
        );

        if($readResult == null){
            http_response_code(401);
        }else if($readResult->password == $data["password"]){
            http_response_code(200);
            echo(json_encode(array("id"=>(string)$readResult->_id, "roles"=>$readResult->roles, "displayName"=>$readResult->name." ".$readResult->surname)));
        } else {
            http_response_code(401);
        }
    }
?>