<?php
    // Portions of this code are adapted from:
    // Traversy, B. (2019) 'PHP REST API - MyBlog', GitHub. Available at: 
    // https://github.com/bradtraversy/php_rest_myblog.

    // Header details explained in users/register.php
    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    // End of external code.

    // My Custom Code 
    // Header to be able to set cookies. Details explained in users/login.php
    header("Access-Control-Allow-Credentials: true");

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

    // Get raw data.
    // Explanation in user/register.php
    $data = json_decode(file_get_contents("php://input"));
    // End of external code.

    // My Custom Code
    $class = new Classes($db);

    try{
        if(isset($_GET["class_name"])){
            $class_name = htmlspecialchars($_GET["class_name"]);
            $class->set_class_capacity(htmlspecialchars($data->class_capacity ?? null));
            $class->set_teacher(htmlspecialchars($data->teacher ?? null), $class_name);
        }else{
            throw new Exception("Class name must be set!", 400);
        }

        if($class->update($class_name)){
            http_response_code(200);
            echo json_encode(array(
                "message" => "Class updated!"
            ));
        }
    }catch(Exception $e){
        //http_response_code($e->getCode());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }

?>