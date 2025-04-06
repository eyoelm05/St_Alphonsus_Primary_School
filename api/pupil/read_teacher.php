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
    require_once __DIR__."/../../src/Models/Pupils.php";
    require_once __DIR__."/../../src/Models/Class.php";
    require_once __DIR__."/../../src/Middleware/auth_middleware.php";

    // Protect route
    $user = authorize_employee();
    // End of my custom code.

    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    // Instantiate database and connect it. 
    $database = new Database();
    $db = $database->connect();
    // End of external code.

    //Instantiate a new pupil
    $pupil = new Pupil($db);
    $class = new Classes($db);

    try{
        // Get class name for $_GET super global variable
        // Sent through params from front-end
        if(isset($_GET["class_name"])){
            // Read pupil in a single class
            $class_name = $_GET["class_name"];
            $data = $class->read_class($class_name);
        }else{
            $class_name = $class->class_teacher($user->username, $user->employee_type);
            $data = $class->read_class($class_name["class_name"]);
        }

        http_response_code(200);
        echo json_encode(array(
            "pupils" => $data
        ));
        
    }catch(Exception $e){
        http_response_code($e->getCode());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>