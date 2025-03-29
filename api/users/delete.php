<?php
    // Headers: Taken from https://github.com/bradtraversy/php_rest_myblog
    // Allows this API to be accessed from any domain.
    header('Access-Control-Allow-Origin: *');
    // Sets the content type to JSON, meaning this API sends and receives JSON data.
    header('Content-Type: application/json');
    // Specifies the request methods that this API can receive. In this case, the API can only be accessed if the request is made with a POST method.
    header('Access-Control-Allow-Methods: DELETE');
    // Specifies the headers that are allowed in the request. This enables the API to accept headers like Content-Type, Access-Control-Allow-Methods, Authorization, and X-Requested-With.
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // My Code 
    // header to be able to set cookies.
    //The server allows credentials to be included in cross-origin HTTP requests.(MDN Web Docs, 2025)
    header("Access-Control-Allow-Credentials: true");

    //Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";
    require_once __DIR__."/../../src/Controller/auth_controller.php";

    //Authenticate current user
    $current_user = authenticate();

    //Instantiate database and connect it. 
    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    try{
        if($user->delete($current_user->username)){
            //Destroy the cookie
            setcookie('auth', '', time() - 2592000, '/');
            echo json_encode(array(
                "message" => "User deleted successfully!"
            ));
        }else{
            throw new Exception ("Unable to delete user!");
        }
    }catch (Exception $e){
        echo json_encode(
            array(
                "message" => $e->getMessage()
            )
        );
    }

?>