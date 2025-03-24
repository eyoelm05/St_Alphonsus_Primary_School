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

        //Create User
        public function register(){

        }

        //Check if user exists
        public function check_user(){
            //Database Query
            $query = 'SELECT COUNT(*) AS no_user FROM users WHERE username = :username';

            //Prepare statement: Used to prepate sql query without the data.
            $stmt = $this->conn->prepare($query);

            //Execute the query with the data
            $stmt->execute(array("username" => $this->username));
            $row = $stmt->fetch();

            //Cast to integer to make sure
            settype($row["no_user"], "integer");

            //If clause to check if a user exists
            if($row['no_user'] !== 0){
                return false;
            } else {
                return true;
            }    
        }
    }
?>