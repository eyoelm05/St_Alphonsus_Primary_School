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
    // Header to be able to set cookies. Details explained in users/login.php
    header("Access-Control-Allow-Credentials: true");


    // Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";
    require_once __DIR__."/../../config/jwt.php";
    require_once __DIR__."/../../Controller/auth_controller.php";
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

    try{
        //Instantiate a new user object.
        //Checks user_type instantiate the correct user object.
        if($data->user_type == "parent"){
                $user = new User($db);
        }elseif($data->user_type == "employee"){
            $user=new Employee_User($db);
        }else{
            throw new Exception ("User type can only take values parent or employee!", 400);
        }

        //Sanitize data 
        $user->set_username(htmlspecialchars($data->username ?? null));
        $user->set_name(htmlspecialchars($data->first_name ?? null),htmlspecialchars($data->middle_initial ?? null), htmlspecialchars($data->last_name ?? null));
        $user->set_phone_no(htmlspecialchars($data->phone_no ?? null));
        $user->set_email(htmlspecialchars($data->email ?? null));
        $user->set_address(htmlspecialchars($data->address ?? null));
        $user->set_sex(htmlspecialchars($data->sex ?? null));
        $user->set_password(htmlspecialchars($data->password ?? null));

        if($data->user_type == "employee"){
            $user->set_background_check(htmlspecialchars($data->background_check ?? null));
            $user->set_date_of_birth(htmlspecialchars($data->date_of_birth ?? null));
            $user->set_start_date(htmlspecialchars($data->start_date ?? null));
        }

        if($data->employee_type == "TA"){
            $user->set_class_name(htmlspecialchars($data->class_name ?? null));
        }

        // Call the update function
        if($user->update($current_user->username)){
            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "User updated successfully"
                )
            );
        }else{
            throw new Exception ("Server Error!", 500);
        }

    }catch(Exception $e){
        http_response_code($e->getCode());
        echo json_encode(
            array(
                "message" => $e->getMessage()
            )
        );
    }
?>