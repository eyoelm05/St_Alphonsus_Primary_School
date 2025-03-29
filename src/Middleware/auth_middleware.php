<?php
    require __DIR__."/../Controller/auth_controller.php";

    function authorize_parent(){
        //Call authenticate function
        $users = authenticate();

        //Check if user type is parent $users->user_type because by default decoding a jwt token gives you an object
        if($users->user_type != "parent"){
            echo json_encode(["message" => "Access Denied: Admins Only"]);
            exit();
        }

        return $users;
    }

    function authorize_employee(){
        //Call authenticate function
        $users = authenticate();

        //Check if user type is employee
        if($users->user_type != "employee"){
            echo json_encode(["message" => "Access Denied: Admins Only"]);
            exit();
        }

        return $users;
    }
?>