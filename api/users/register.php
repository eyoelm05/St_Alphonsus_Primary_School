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

    //Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";

    //Instantiate database and connect it. 
    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    $database = new Database();
    $db = $database->connect();

    //My code
    //Instantiate a new user object
    $user = new User($db);

    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    //This code get's the data sent from an extrenal server.
    //Description of each part.
    //json_decode()- decodes a json data and converts into php associative array.
    //file_get_contents() - used to read raw data from files or links.
    //php://input - is a read only stream that is used to access raw post data.
    $data = json_decode(file_get_contents("php://input"));
    
    //My code
    //Sanitize data using htmlspecialchars()
    //Use setters to set values in user object
    $user->set_username(htmlspecialchars($data->username));
    $user->set_name(htmlspecialchars($data->first_name, $data->middle_initial, $data->last_name));
    $user->set_phone_no(htmlspecialchars($data->phone_no));
    $user->set_email(htmlspecialchars($data->email));
    $user->set_address(htmlspecialchars($data->address));
    $user->set_sex(htmlspecialchars($data->sex));
    $user->set_user_type(htmlspecialchars($data->user_type));
    $user->set_password(htmlspecialchars($data->password));
    
    
?>