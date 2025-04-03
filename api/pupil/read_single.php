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
    require_once __DIR__."/../../src/Controller/auth_controller.php";

    // Protect route
    $user = authenticate();
    // End of my custom code.

    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    // Instantiate database and connect it. 
    $database = new Database();
    $db = $database->connect();
    // End of external code.

    // Instantiate a new pupil
    $pupil = new Pupil($db);

    try{
        // Get Id for $_GET super global variable
        if(isset($_GET["id"])){
            $id = $_GET["id"];
            
            // Check the user accessing the data
            if($user->user_type == "parent"){
                // Check if parent is related to the pupil they are trying to access
                if($pupil->check_parent($user->username, $id)){
                    // Read pupil wit the specific id.
                    $data = $pupil->read_single($id);
                }else{
                    throw new Exception ("Unauthorized!", 401);
                } 
            }elseif($user->user_type == "employee"){
                $data = $pupil->read_single($id);
            }else{
                throw new Exception ("Unauthorized!", 401);
            }
        }

        http_response_code(200);
        echo json_encode(array(
            "pupil" => $data
        ));
        
    }catch(Exception $e){
        http_response_code($e->getCode());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>