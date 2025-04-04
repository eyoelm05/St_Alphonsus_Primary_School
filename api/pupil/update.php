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

    // Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Pupils.php";
    require_once __DIR__."/../../src/Middleware/auth_middleware.php";

    // Protect route
    $user = authorize_parent();
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
    // Instantiate a new pupil
    $pupil = new Pupil($db);
    
    try{        
        // Get Id for $_GET super global variable
        if(isset($_GET["id"])){
            $id = $_GET["id"];
            // Check if parent is related to the pupil they are trying to access
            if($pupil->check_parent($user->username, $id)){
                // Sanitize and set data
                $pupil->set_name(htmlspecialchars($data->first_name ?? null),htmlspecialchars($data->middle_initial ?? null), htmlspecialchars($data->last_name ?? null));
                $pupil->set_sex(htmlspecialchars($data->sex ?? null));
                $pupil->set_address(htmlspecialchars($data->address ?? null));
                $pupil->set_date_of_birth(htmlspecialchars($data->date_of_birth ?? null));
                $pupil->set_class_name(htmlspecialchars($data->class_name ?? null));

                // Add medicals only if it exists
                if($data->medicals ?? null){
                    $medicals = [];
                    foreach($data->medicals as $medical_info){
                        array_push($medicals, htmlspecialchars($medical_info));
                    }
                    $pupil->set_medicals($medicals);
                }

                // Call update function
                if($pupil->update($id)){
                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "Pupil updated successfully!",
                        "user_type" => $user->user_type
                    ));
                }else{
                    throw new Exception ("Server Error!", 500);
                }
            }else{
                throw new Exception ("Unauthorized!", 401);
            }   
        }else{
            throw new Exception("Id must be set to update data!", 400);
        }
    }catch(Exception $e){
        http_response_code($e->getCode());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>