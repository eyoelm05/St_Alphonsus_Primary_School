<?php
    class User {
        //Variable for db connection
        private $conn;

        //Properties of user
        private $username;
        private $first_name;
        private $middle_intial;
        private $last_name;
        private $phone_no;
        private $address;
        private $sex;
        private $user_type;
        private $password_hash;

        public function __construct($db){
            $this -> conn = $db;
        }
    }
?>