<?php
    // Headers: Taken from https://github.com/bradtraversy/php_rest_myblog
    // Allows this API to be accessed from any domain.
    header('Access-Control-Allow-Origin: *');
    // Sets the content type to JSON, meaning this API sends and receives JSON data.
    header('Content-Type: application/json');
    // Specifies the request methods that this API can receive. In this case, the API can only be accessed if the request is made with a POST method.
    header('Access-Control-Allow-Methods: PUT');
    // Specifies the headers that are allowed in the request. This enables the API to accept headers like Content-Type, Access-Control-Allow-Methods, Authorization, and X-Requested-With.
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    //Import required files.
    require_once __DIR__."/../../config/Database.php";
    require_once __DIR__."/../../src/Models/Users.php";
    require_once __DIR__."/../../src/Controller/auth_controller.php";

    //Authenticate current user
    $current_user = authenticate();

    //Instantiate database and connect it. 
    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    $database = new Database();
    $db = $database->connect();

    //Taken from https://github.com/bradtraversy/php_rest_myblog.
    //This code get's the data sent from an external server.
    //Description of each part.
    //json_decode()- decodes a json data and converts into php associative array.
    //file_get_contents() - used to read raw data from files or links.
    //php://input - is a read only stream that is used to access raw post data.
    $data = json_decode(file_get_contents("php://input"));
    
    //My code
    try{
        //Instantiate a new user object.
        //Checks user_type instantiate the correct user object.
        if($data->user_type == "parent"){
                $user = new User($db);
        }elseif($data->user_type == "employee"){
            $user=new Employee_User($db);
        }else{
            throw new Exception ("User type can only take values parent or employee!!");
        }

        //Sanitize data using htmlspecialchars()
        //Use setters to set values in user object
        $user->set_username(htmlspecialchars($data->username));
        $user->set_name(htmlspecialchars($data->first_name),htmlspecialchars($data->middle_initial), htmlspecialchars($data->last_name));
        $user->set_phone_no(htmlspecialchars($data->phone_no));
        $user->set_email(htmlspecialchars($data->email));
        $user->set_address(htmlspecialchars($data->address));
        $user->set_sex(htmlspecialchars($data->sex));
        $user->set_password(htmlspecialchars($data->password));

        if($data->user_type == "employee"){
            $user->set_background_check(htmlspecialchars($data->background_check));
            $user->set_date_of_birth(htmlspecialchars($data->date_of_birth));
            $user->set_start_date(htmlspecialchars($data->start_date));
        }

        if($data->employee_type == "TA"){
            $user->set_class_name(htmlspecialchars($data->class_name));
        }

        if($user->update($current_user->username)){
            //Change associative array to JSON.
            echo json_encode(
                array(
                    "message" => "User updated successfully"
                )
            );
        }
    }catch(Exception $e){
        echo json_encode(
            array(
                "message" => $e->getMessage()
            )
        );
    }
?>