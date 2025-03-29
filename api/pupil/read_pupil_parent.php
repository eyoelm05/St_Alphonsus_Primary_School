<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    //Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Pupils.php";
    require_once __DIR__."/../../src/Middleware/auth_middleware.php";

    //Protect route
    $user = authorize_parent();

    //Instantiate database and connect it. 
    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    $database = new Database();
    $db = $database->connect();

    //Instantiate a new pupil
    $pupil = new Pupil($db);

    try{
        //Get Id
        if(!isset($_GET["id"])){
            throw new Exception ("Wrong route!");
        }
        $id = $_GET["id"];

        $pupil->read_parent($id, $user->username);
    }catch(Exception $e){
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>