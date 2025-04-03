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
        // Check if student is existing and id is set
        if($data->exists && $data->id){
            // Check if relationship exists
            if($data->relationship){
                // Execute add_pupil
                if($pupil->add_parent(htmlspecialchars($data->id),$user->username, htmlspecialchars($data->relationship))){
                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "Pupil added successfully"
                    ));
                }else{
                    throw new Exception ("Server Error!", 500);
                }
            } else{
                throw new Exception ("Relationship can't be empty!", 400);
            }
        }elseif($data->exist && !$data->id){
            throw new Exception ("Please input id of the registered student!", 400);
        }else{
            // Sanitize and set data
            $pupil->set_name(htmlspecialchars($data->first_name),htmlspecialchars($data->middle_initial), htmlspecialchars($data->last_name));
            $pupil->set_sex(htmlspecialchars($data->sex));
            $pupil->set_address(htmlspecialchars($data->address));
            $pupil->set_date_of_birth(htmlspecialchars($data->date_of_birth));
            $pupil->set_class_name(htmlspecialchars($data->class_name));

            // add medicals only if it exists
            if($data->medicals){
                $medicals = [];
                foreach($data->medicals as $medical_info){
                    array_push($medicals, htmlspecialchars($medical_info));
                }

                $pupil->set_medicals($medicals);
            }

            // Check if relationship exists
            if($data->relationship){
                // Execute add_pupil
                if($pupil->add_pupil($user->username, htmlspecialchars($data->relationship))){
                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "Pupil added successfully"
                    ));
                }else{
                    throw new Exception ("Server Error!", 500);
                }
            } else{
                throw new Exception ("Relationship can't be empty!", 400);
            }
        }
    }catch(Exception $e){
        http_response_code($e->get_code());
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>