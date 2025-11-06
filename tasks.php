<?php
    require 'vendor/autoload.php';   
    require 'config.php';

    $connectionString = 'mongodb+srv://'.$CFG->mdbuser.':'.$CFG->mdbpassword.'@'.$CFG->mdbserver.'/?retryWrites=true&w=majority&appName=Cluster0';
    $client = new MongoDB\Client($connectionString);

    $database = $CFG->mdbdatabase;
    $collection = $client->$database->tasks;

    $data = json_decode(file_get_contents("php://input"), true);

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        if($_REQUEST["action"] == 1){
            $filter = [
                'user' => new MongoDB\BSON\ObjectID($data["user"]),
                'status' => $data["status"]
            ];
            if($collection->countDocuments($filter,[]) == 0){
                echo(json_encode(null));
                http_response_code(200);
            } else {
                $readResult = $collection->find(
                    $filter,
                    []
                );
                $taskarray = [];
                foreach($readResult as $result){
                    $task = array(
                        "taskid"=>(string)$result->_id, 
                        "name"=>$result->name, 
                        "description"=>$result->description, 
                        "parent"=>$result->parent, 
                        "priority"=>$result->priority,
                        "externalResources"=>$result->externalResources,
                        "internalResources"=>$result->internalResources,
                        "completed"=>$result->completed,
                    );
                    array_push($taskarray, $task);   
                }
                echo(json_encode($taskarray));
                http_response_code(200);
            }
        } else if($_REQUEST["action"] == 2){
            $filter = [
                    '_id' => new MongoDB\BSON\ObjectId((String)$data["taskId"])
            ];
            $readResult1 = $collection->findOne(
                $filter,
                []
            );
            $collection = $client->$database->status;
            $readResult2 = $collection->findOne(
                [
                    'numericId' => $readResult1["status"]
                ],
                []
            );
            $taskarray = [];
            $task = array(
                "name"=>$readResult1["name"], 
                "description"=>$readResult1["description"], 
                "externalResources"=>$readResult1["externalResources"],
                "internalResources"=>$readResult1["internalResources"],
                "status"=>$readResult2["status"],
                "priority"=>$readResult1["priority"],
                "completed"=>$readResult1["completed"],
            );
            echo(json_encode($task));
            http_response_code(200);
        
        } else if($_REQUEST['action'] == 3){
            if(isset($data["user"], $data["name"], $data["description"], $data["parent"], $data["status"], $data["priority"])){
                try{
                    $insertOne = $collection->insertOne([
                        'user' => new MongoDB\BSON\ObjectID($data["user"]),
                        'name' => $data["name"],
                        'description' => $data["description"],
                        'parent' => $data["parent"],
                        'status' => $data["status"],
                        'priority' => $data["priority"],
                        'externalResources' => $data["extResources"],
                        'internalResources' => $data["intResources"],
                        'completed' => false,
                    ]);

                    if($data["priority"] == 1){
                        $readResult = $collection->find(
                            [
                                'parent' => $data["parent"],
                                'status' => $data["status"],
                            ],
                            []
                        );

                        foreach($readResult as $result){
                            if($result->_id != $insertOne->getInsertedId()){
                                $updateResult = $collection->updateOne(
                                    [
                                        '_id' => $result->_id
                                    ],
                                    [
                                        '$set' => [
                                            'priority' => $result->priority + 1,
                                        ] 
                                    ]
                                );
                            }
                        }
                    }
                } catch (Exception $e){
                    http_response_code(500);
                }
                http_response_code(200);
            }
        }
    } else if($_SERVER['REQUEST_METHOD'] == "PUT"){
        $updateResult = $collection->updateOne(
            [
                "_id" => new MongoDB\BSON\ObjectId($data["id"])
            ],
            [
                '$set' => [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'externalResources' => $data['extResources'],
                    'internalResources' => $data['intResources'],
                    'completed' => $data['completed'],
                ]
            ]
        );
    }
?>  