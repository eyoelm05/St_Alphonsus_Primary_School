<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    //Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Pupils.php";
    require_once __DIR__."/../../src/Middleware/auth_middleware.php";

    //Protect route
    $user = authorize_employee();

    //Instantiate database and connect it. 
    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    $database = new Database();
    $db = $database->connect();

    //Instantiate a new pupil
    $pupil = new Pupil($db);

    try{
        //Get Id
        if(isset($_GET["id"])){
            $id = $_GET["id"];
            $data = $pupil->read_single($id);
        }elseif(isset($_GET["class_name"])){
            $class_name = $_GET["class_name"];
            $data = $pupil->read_class($class_name);
        }

        echo json_encode(array(
            "pupils" => $data
        ));
        
    }catch(Exception $e){
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>