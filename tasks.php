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
            $readResult = $collection->findOne(
                $filter,
                []
            );
            $task = array(
                "name"=>$readResult["name"], 
                "description"=>$readResult["description"], 
                "parent"=>$readResult["parent"],
                "externalResources"=>$readResult["externalResources"],
                "internalResources"=>$readResult["internalResources"],
                "status"=>$readResult["status"],
                "priority"=>$readResult["priority"],
                "completed"=>$readResult["completed"],
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
        $readResult = $collection->findOne(
            [
                '_id' => new MongoDB\BSON\ObjectId((String)$data["id"]),
            ],
            []
        );
        $previousCompleted = $readResult["completed"];
        $parent = $readResult["parent"];
        $oldStatus = $readResult["status"];
        $user = $readResult['user'];

        $updateResult = $collection->updateOne(                                                        //posodobitev dokumenta
            [
                "_id" => new MongoDB\BSON\ObjectId($data["id"])
            ],
            [
                '$set' => [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'externalResources' => $data['extResources'],
                    'internalResources' => $data['intResources'],
                    'completed' => $data['completed'],
                ]
            ]
        );
        
        if($oldStatus != $data['status']){                                                            //posodobitev statusa naloge
            $readResult = $collection->find(
                [
                    'user' => $user,
                    'parent' => "0",
                    'status' => $data['status'],
                ]
            );
            
            $lowestPriority = 0;
            foreach($readResult as $result){
                if($result['priority'] > $lowestPriority){
                    $lowestPriority = $result['priority'];
                }
            }
            
            $updateResult = $collection->updateOne(                                                        
                [
                    "_id" => new MongoDB\BSON\ObjectId($data["id"])
                ],
                [
                    '$set' => [
                        'priority' => $lowestPriority + 1,
                    ]
                ]
            );
            
            $readResult = $collection->find(
                [
                    'parent' => $data['id'],
                ]
            );
            $tasksForUpdate = [];
            foreach($readResult as $result){
                array_push($tasksForUpdate, (String)$result->_id);
            }
            $i = 0;
            while($i < count($tasksForUpdate)){
                $readResult = $collection->find(
                    [
                        'parent' => $tasksForUpdate[$i],
                    ]
                );
                foreach($readResult as $result){
                    array_push($tasksForUpdate, (String)$result->_id);
                }
                $updateResult = $collection->updateOne(
                    [
                        '_id' => new MongoDB\BSON\ObjectId((String)$tasksForUpdate[$i]),
                    ],
                    [
                        '$set' =>
                            [
                                'status' => $data['status'],
                            ]
                    ]
                );
                $i++;
            }
        }
    } else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        if(isset($data["id"])){
            $collection = $client->$database->tasks;
            $readResult1 = $collection->findOne(
                [
                    '_id' => new MongoDB\BSON\ObjectId((String)$data["id"]),
                ],
                []
            );
            $updateResult = $collection->updateMany(
                [
                    'parent' => $readResult1["parent"],
                    'priority' => ['$gt' => $readResult1["priority"]]
                ],
                [
                    '$inc' => [
                        'priority' => -1,
                    ] 
                ]
            );

            $deleteResult = $collection->deleteMany(
                ['_id' => new MongoDB\BSON\ObjectId((String)$data["id"])
                ]
            );
            http_response_code(200);
        }
    }
?>  