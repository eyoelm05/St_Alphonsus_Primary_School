<?php
    // The code in this file is entirely custom made by me.
    
    //Import required files
    require __DIR__.'/../../vendor/autoload.php';
    require __DIR__.'/../../config/jwt.php';
    

    function authenticate() {
        // Create jwt object
        $jwt_object = new JWT_TOKEN();

        // Get cookie from server
        if (isset($_COOKIE["auth"])) {
            $jwt = $_COOKIE["auth"];
        } else {
            $jwt = null;
        }

        // If there is no jwt send message unauthorized
        if (!$jwt) {
            http_response_code(401);
            echo json_encode(array(
                "message" => "Unauthorized"
            ));
            exit();
        }

        try{
            // Verify token received 
            $decoded = $jwt_object->verify_token($jwt);
            return $decoded["data"];
        }catch (Exception $e){
            http_response_code($e->getCode());
            echo json_encode(array(
                "message" => $e->get_message()
            ));
        }
    }
?>