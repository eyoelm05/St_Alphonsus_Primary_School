<?php
    // Headers: Taken from https://github.com/bradtraversy/php_rest_myblog
    // Allows this API to be accessed from any domain.
    header('Access-Control-Allow-Origin: *');
    // Sets the content type to JSON, meaning this API sends and receives JSON data.
    header('Content-Type: application/json');
    // Specifies the request methods that this API can receive. In this case, the API can only be accessed if the request is made with a POST method.
    header('Access-Control-Allow-Methods: POST');
    // Specifies the headers that are allowed in the request. This enables the API to accept headers like Content-Type, Access-Control-Allow-Methods, Authorization, and X-Requested-With.
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // My Code 
    // header to be able to set cookies.
    //The server allows credentials to be included in cross-origin HTTP requests.(MDN Web Docs, 2025)
    header("Access-Control-Allow-Credentials: true");

    //Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Pupils.php";
    require_once __DIR__."/../../src/Middleware/auth_middleware.php";

    //Protect route
    $user = authorize_parent();

    //Instantiate database and connect it. 
    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    $database = new Database();
    $db = $database->connect();

    //Instantiate a new pupil
    $pupil = new Pupil($db);

    //Get data from front-end
    $data = json_decode(file_get_contents("php://input"));
    
    try{        
        //Get Id
        if(isset($_GET["id"])){
            $id = $_GET["id"];
            //Check if parent is related to the pupil they are trying to access
            if($pupil->check_parent($user->username, $id)){
                //Sanitize and set data
                $pupil->set_name(htmlspecialchars($data->first_name),htmlspecialchars($data->middle_initial), htmlspecialchars($data->last_name));
                $pupil->set_sex(htmlspecialchars($data->sex));
                $pupil->set_address(htmlspecialchars($data->address));
                $pupil->set_date_of_birth(htmlspecialchars($data->date_of_birth));
                $pupil->set_class_name(htmlspecialchars($data->class_name));

                //Add medicals only if it exists
                if($data->medicals){
                    $medicals = [];
                    foreach($data->medicals as $medical_info){
                        array_push($medicals, htmlspecialchars($medical_info));
                    }
                    $pupil->set_medicals($medicals);
                }
                if($pupil->update($id)){
                    echo json_encode(array(
                        "message" => "Pupil updated successfully"
                    ));
                }else{
                    throw new Exception ("Server Error.");
                }
            }else{
                throw new Exception ("Unauthorized!");
            }   
        }else{
            throw new Exception("Id must be set to update data!");
        }
    }catch(Exception $e){
        echo json_encode(array(
            "message" => $e->getMessage() 
        ));
    }
?>