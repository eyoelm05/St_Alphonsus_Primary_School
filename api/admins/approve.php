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

    // Get raw data.
    // Explanation in user/register.php
    $data = json_decode(file_get_contents("php://input"));
    // End of external code.

    // My Custom Code
    $user = new Employee_User($db);
    $admin = new Admin($db);
    try{
        if(isset($_GET["username"])){
            $user->set_username(htmlspecialchars($_GET["username"]));
            $user->set_background_check(htmlspecialchars($data->background_check ?? null));
            $user->set_date_of_birth(htmlspecialchars($data->date_of_birth ?? null));
            $user->set_start_date(htmlspecialchars($data->start_date ?? null));
            $user->set_employee_type(htmlspecialchars($data->employee_type ?? null));

            // Data sent for teacher assistants only
            if($data->employee_type === "TA"){
                $user->set_class_name(htmlspecialchars($data->class_name ?? null));
            }

            $message = $admin->approve($user);
            if($message){
                http_response_code(200);
                echo json_encode(array(
                    "message" => $message
                ));
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }else{
            throw new Exception("Username must be set!", 400);
        }

    }catch(Exception $e){
        http_response_code($e->getCode());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>