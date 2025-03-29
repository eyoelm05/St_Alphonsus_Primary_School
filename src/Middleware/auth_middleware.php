<?php
    //Import required files
    require __DIR__.'/../../vendor/autoload.php';
    require __DIR__.'/../../config/jwt.php';
    

    $jwt_object = new JWT_TOKEN();
    function authenticate() {
        //Get cookie from server
        if (isset($_COOKIE["auth"])) {
            $jwt = $_COOKIE["auth"];
        } else {
            $jwt = null;
        }

        //If there is no jwt send message unauthorized
        if (!$jwt) {
            echo json_encode(array(
                "message" => "Unauthorized"
            ));
            exit();
        }

        try{
            //Verify token received 
            $decoded = $jwt_object->verify_token($jwt);
        }catch (Exception $e){
            echo json_encode(array(
                "message" => $e->get_message()
            ));
        }
    }
?>