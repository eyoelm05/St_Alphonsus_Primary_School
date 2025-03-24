<?php
    class User {
        //Variable for db connection
        protected $conn;

        //Properties of user
        protected $username;
        protected $first_name;
        protected $middle_initial;
        protected $last_name;
        protected $phone_no;
        protected $email;
        protected $address;
        protected $sex;
        protected $user_type;
        protected $password_hash;

        public function __construct($db){
            $this -> conn = $db;
        }

        //Create User method
        public function register(){
            //Query used to insert a user
            $query = "INSERT INTO users (username, first_name, middle_initial, last_name, email, phone_no, address, sex, user_type, password_hash) 
          VALUES (:username, :first_name, :middle_initial, :last_name, :email, :phone_no, :address, :sex, :user_type, :password_hash)";


            //Prepare statement
            $stmt = $this->conn->prepare($query);

            if($stmt->execute([
                "username" => $this->username,
                "first_name" => $this->first_name,
                "middle_initial" => $this->middle_initial,
                "last_name" => $this->last_name ,
                "email" => $this->email,
                "phone_no" => $this->phone_no,
                "address" => $this->address,
                "sex" => $this->sex,
                "user_type" => $this->user_type,
                "password_hash" => $this->password_hash
            ])){
                return true;
            }

            return false;
        }

        //Check if user exists
        public function check_user(){
            //Query to count if there is a user with a specfic username
            $query = 'SELECT COUNT(*) AS no_user FROM users WHERE username = :username';

            //Prepare statement: Used to prepate sql query without the data.
            $stmt = $this->conn->prepare($query);

            //Execute the query with the data securely 
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

    class Parent_User extends User {
        public function __construct($db){
            parent::__construct($db);
        }

        public function register(){
            //First register user
            if(parent::register()){
                $query = "INSERT INTO parents(username) VALUES (:username)";
                $stmt = $this->conn->prepare($query);

                if($stmt->execute([
                    "username" => $this->username
                ])){
                    return true;
                }
                return false;
                
            }
        }
    }
?>