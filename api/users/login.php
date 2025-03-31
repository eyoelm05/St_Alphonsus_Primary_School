<?php
    // Portions of this code are adapted from:
    // Traversy, B. (2019) 'PHP REST API - MyBlog', GitHub. Available at: 
    // https://github.com/bradtraversy/php_rest_myblog.

    // Header details explained in users/register.php
    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    // End of external code.

    // My Custom Code 
    // Header to be able to set cookies.
    // The server allows credentials to be included in cross-origin HTTP requests. Description taken from (MDN Web Docs, 2025).
    header("Access-Control-Allow-Credentials: true");


    // Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";
    require_once __DIR__."/../../config/jwt.php";
    // End of my custom code.

    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    // Instantiate database and connect it. 
    $database = new Database();
    $db = $database->connect();

    // Get raw data.
    // Explanation in user/register.php
    $data = json_decode(file_get_contents("php://input"));
    // End of external code.

    // My custom code
    // Create an instance of user and jwt objects.
    $user = new User($db);
    $jwt_object = new JWT_TOKEN();

    try{
        // Check if the raw data has the required values.
        if (!$data || !isset($data->username) || !isset($data->password)) {
            throw new Exception ("Please input both username and password", 400);
        }

        //Sanitize data
        $username = htmlspecialchars($data->username);
        $password = htmlspecialchars($data->password);

        //Call login function to verify username and password.
        $login_data = $user->login($username,$password);

        // Check if employee_type exists and then add it to token payload.
        // Use issue token function from JWT_TOKEN object to create the token.
        if($login_data["employee_type"]){
            $token = $jwt_object->issue_token($login_data["username"], $login_data["user_type"], $login_data["employee_type"]);
        }else{
            // If employee_type doesn't exist just store username and user type inside the token payload.
            $token = $jwt_object->issue_token($login_data["username"], $login_data["user_type"]);
        }

        // Set cookie auth in the front-end with the token value.
        setcookie("auth", $token,time() + 2*60*60, "/", "",true, true);

        http_response_code(200);
        echo json_encode(
            array(
                "message" => "User logged in."
            )
        );

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