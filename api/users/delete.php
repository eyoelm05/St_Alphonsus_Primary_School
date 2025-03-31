<?php
    // Portions of this code are adapted from:
    // Traversy, B. (2019) 'PHP REST API - MyBlog', GitHub. Available at: 
    // https://github.com/bradtraversy/php_rest_myblog.
    // And
    // Nixon, R. (2025). Learning PHP, MySQL & JavaScript. ‘O’Reilly Media, Inc.’

    // Header details explained in users/register.php
    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    // End of external code.

    // My Custom Code 
    // Header to be able to set cookies. Details explained in users/login.php
    header("Access-Control-Allow-Credentials: true");

    // Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";
    require_once __DIR__."/../../config/jwt.php";
    require_once __DIR__."/../../src/Controller/auth_controller.php";
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
    // Authenticate current user. Function explanation found in src/controller/auth_controller.php
    $current_user = authenticate();

    $user = new User($db);

    try{
        if($user->delete($current_user->username)){
            // End of my custom code.
            
            // Adapted from Nixon, R. (2025). Learning PHP, MySQL & JavaScript. ‘O’Reilly Media, Inc.’
            // Destroy the cookie.
            setcookie('auth', '', time() - 2592000, '/');
            //End of external code.

            // My custom code
            http_response_code(200);
            echo json_encode(array(
                "message" => "User deleted successfully!"
            ));
        }else{
            throw new Exception ("Server Error!", 500);
        }
    }catch (Exception $e){
        http_response_code($e->getCode());
        echo json_encode(
            array(
                "message" => $e->getMessage()
            )
        );
    }

?>