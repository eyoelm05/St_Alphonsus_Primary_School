<?php
    // Portions of this code are adapted from:
    // Traversy, B. (2019) 'PHP REST API - MyBlog', GitHub. Available at: 
    // https://github.com/bradtraversy/php_rest_myblog.

    // Header details explained in users/register.php
    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    // No other header is required as this api will be accessed through get request.
    // End of external code.

    // My custom code.
    // Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Admins.php";
    require_once __DIR__."/../../src/Middleware/auth_middleware.php";

    // Protect route
    $user = authorize_admin();
    // End of my custom code.

    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    // Instantiate database and connect it. 
    $database = new Database();
    $db = $database->connect();
    // End of external code.

    // My Custom Code
    $admin = new Admin($db);

    try{
        $data = $admin->not_approved($user->username);

        http_response_code(200);
        echo json_encode(array(
            "employees" => $data
        ));
        
    }catch(Exception $e){
        http_response_code($e->getCode());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }

?>