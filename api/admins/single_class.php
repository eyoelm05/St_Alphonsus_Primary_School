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
    require_once __DIR__."/../../src/Models/Class.php";
    require_once __DIR__."/../../src/Middleware/auth_middleware.php";

    // Protect route
    $user = authorize_admin();
    // End of my custom code.

    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    // Instantiate database and connect it. 
    $database = new Database();
    $db = $database->connect();
    // End of external code.

    // Instantiate a new pupil
    $class = new Classes($db);

    try{
        // Get id from $_GET super global variable
        if(isset($_GET["class_name"])){
            // read single class
            $data = $class->read_single_class($_GET["class_name"]);
            $available_teachers = $class->available_teachers($_GET["class_name"]);
        }else{
            throw new Exception ("Class name must be set!", 400);
        }

        http_response_code(200);
        echo json_encode(array(
            "class" => $data,
            "teachers" => $available_teachers
        ));
        
    }catch(Exception $e){
        http_response_code($e->getCode());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>