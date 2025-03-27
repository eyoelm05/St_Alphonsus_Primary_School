<?php
    // Headers: Taken from https://github.com/bradtraversy/php_rest_myblog
    // Allows this API to be accessed from any domain.
    header('Access-Control-Allow-Origin: *');
    // Sets the content type to JSON, meaning this API sends and receives JSON data.
    header('Content-Type: application/json');
    // Specifies the request methods that this API can receive. In this case, the API can only be accessed if the request is made with a POST method.
    header('Access-Control-Allow-Methods: POST');
    // Specifies the headers that are allowed in the request. This enables the API to accept headers like Content-Type, Access-Control-Allow-Methods, Authorization, and X-Requested-With.
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // My Code 
    // header to be able to set cookies.
    //The server allows credentials to be included in cross-origin HTTP requests.(MDN Web Docs, 2025)
    header("Access-Control-Allow-Credentials: true");


    //Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";
    require_once __DIR__."/../../config/jwt.php";

    //Instantiate database and connect it. 
    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    $database = new Database();
    $db = $database->connect();

    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    //This code get's the data sent from an external server.
    //Description of each part.
    //json_decode()- decodes a json data and converts into php associative array.
    //file_get_contents() - used to read raw data from files or links.
    //php://input - is a read only stream that is used to access raw post data.
    $data = json_decode(file_get_contents("php://input"));

    //Create an instance of user and jwt objects.
    $user = new User($db);
    $jwt_object = new JWT_TOKEN();



    try{
        //Check if $data has a value
        if (!$data || !isset($data->username) || !isset($data->password)) {
            throw new Exception ("Please input both username and password");
        }
        //Sanitize data
        $username = htmlspecialchars($data->username);
        $password = htmlspecialchars($data->password);

        //Call in login function to verify username and password.
        $login_data = $user->login($username,$password);

        //Check if employee_type exists and then add it token if it does.
        //If it doesn't just store username and user type inside the token.
        //Use issue token from JWT_TOKEN object to create the token.
        if($login_data["employee_type"]){
            $token = $jwt_object->issue_token($login_data["username"], $login_data["user_type"], $login_data["employee_type"]);
        }else{
            $token = $jwt_object->issue_token($login_data["username"], $login_data["user_type"]);
        }
        setcookie("auth", $token,time() + 2*60*60, "/", true, true);
        echo json_encode(
            array(
                "message" => "User logged in."
            )
            );
    }catch(Exception $e){
        echo json_encode(
            array(
                "message" => $e->getMessage()
            )
        );
    }


?>