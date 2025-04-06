<?php
    // Portions of this code are adapted from:
    // Traversy, B. (2019) 'PHP REST API - MyBlog', GitHub. Available at: 
    // https://github.com/bradtraversy/php_rest_myblog.


    // Headers: adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    // Allows this API endpoint to be accessed from any domain.
    header('Access-Control-Allow-Origin: *');

    // Specifies that this API endpoint will send responses in JSON format.  
    // Notifies the front-end that the API's response data type is JSON. 
    header('Content-Type: application/json');

    // Specifies the request methods that this API can receive. 
    //In this case, this API endpoint can only be accessed if the request is made using the POST method.
    header('Access-Control-Allow-Methods: POST');

    // Specifies the headers that are allowed in the request.
    // This enables the API to accept headers like Content-Type, Access-Control-Allow-Methods, Authorization, and X-Requested-With.
    // X-requested-with allows this api to identify AJAX requests.
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    // End of external code reference.



    // My custom code
    // Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";
    //End of my custom code.


    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    // Instantiate database and connect it.
    $database = new Database();
    $db = $database->connect();

    // This code get's the data sent from an external server.
    // Description of each part.
    // json_decode()- decodes a json data and converts into php associative array.
    // file_get_contents() - used to read raw data from files or links.
    // php://input - is a read only stream that is used to access raw post data.
    $data = json_decode(file_get_contents("php://input"));
    // End of external code reference.

    // My custom code
    // Instantiate user class.
    $user = new User($db);
    try{
        // Sanitize data using htmlspecialchars()
        // Use setters to set values in user object
        // If the value doesn't exist, each setter will receive null. Function of ?? null
        $user->set_username(htmlspecialchars($data->username ?? null));

        // Checks if user already exists  
        if(!$user->check_user()){
            throw new Exception("User already exists!", 409);
        }
        $user->set_name(htmlspecialchars($data->first_name ?? null),htmlspecialchars($data->middle_initial ?? null), htmlspecialchars($data->last_name ?? null));
        $user->set_phone_no(htmlspecialchars($data->phone_no ?? null));
        $user->set_email(htmlspecialchars($data->email ?? null));

        // Handle duplicate emails.
        if(!$user->check_email()){
            throw new Exception("Email already exists!", 409);
        }

        $user->set_address(htmlspecialchars($data->address ?? null));
        $user->set_sex(htmlspecialchars($data->sex ?? null));
        $user->set_user_type(htmlspecialchars($data->user_type ?? null));
        $user->set_password(htmlspecialchars($data->password ?? null));

        // Register User
        if($user->register()){
            // Change associative array to JSON.
            // Send the response code and the message.
            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "User created successfully!"
                )
            );
        }else{
            throw new Exception("Server Error.", 500);
        }
    }catch(Exception $e){
        // If an exception occurs, send the HTTP code and its message.
        http_response_code($e->getCode());
        echo json_encode(
            array(
                "message" => $e->getMessage()
            )
        );
    }
?>