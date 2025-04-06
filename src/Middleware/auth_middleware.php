<?php
    // The code in this file is entirely custom made by me.
    
    require __DIR__."/../Controller/auth_controller.php";

    function authorize_parent(){
        // Call authenticate function
        $users = authenticate();

        // Check if user type is parent $users->user_type because by default decoding a jwt token gives you an object
        if($users->user_type != "parent"){
            http_response_code(401);
            echo json_encode(["message" => "Access Denied: Parents only"]);
            exit();
        }

        return $users;
    }

    function authorize_employee(){
        // Call authenticate function
        $users = authenticate();

        // Check if user type is employee
        if($users->user_type != "employee"){
            http_response_code(401);
            echo json_encode(["message" => "Access Denied: Employees only"]);
            exit();
        }

        return $users;
    }

    function authorize_admin(){
        // Call authenticate function
        $users = authenticate();

        // Check if user type is admin
        if($users->employee_type != "A"){
            http_response_code(401);
            echo json_encode(["message" => "Access Denied: Admins only"]);
            exit();
        }

        return $users;
    }
?>