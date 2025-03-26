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

        //Setter functions for each value
        public function set_username($username){
            //Make sure username exists
            if(empty($username)){
                throw new Exception("Username can't be empty!");
            }
            //Use preg_match to make sure that username should have the same pattern as the regular expression. Trim is used to get rid of white space.
            if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', trim($username))) {
                throw new Exception("Invalid username! Must be 3-20 characters long and contain only letters, numbers, or underscores.");
            };
            //Assign Value
            //Making this an else block is possible, but it's not necessary because the code won't reach this part if any of the if clauses become true.
            $this->username = $username;
        }

        public function setName($first_name, $middle_initial, $last_name) {
            // Trim whitespace
            $first_name = trim($first_name);
            $middle_initial = trim($middle_initial);
            $last_name = trim($last_name);
        
            // Check if first and last name are empty
            if (empty($first_name)) {
                throw new Exception("First name can't be empty!");
            }
            if (empty($last_name)) {
                throw new Exception("Last name can't be empty!");
            }
        
            // Ensure names contain only letters
            if (!ctype_alpha($first_name) || !ctype_alpha($last_name)) {
                throw new Exception("Names must contain only alphabetic characters.");
            }
        
            // Middle initial is optional, but if provided, must be a single alphabetic character
            if (!empty($middle_initial) && (!ctype_alpha($middle_initial) || strlen($middle_initial) > 1)) {
                throw new Exception("Middle initial must be a single letter.");
            }
        
            // Assign values
            $this->first_name = $first_name;
            $this->middle_initial = $middle_initial;
            $this->last_name = $last_name;
        }

        public function set_phone_no($phone_no){
            //Trim white space
            $phone_no = trim($phone_no);

            //Check if phone_no exists
            if(empty($phone_no)){
                throw new Exception("Phone number can't be empty!");
            }
            //Check if phone_no is a valid UK number
            if(!preg_match('/^07\d{9}$/',$phone_no)){
                throw new Exception("Invalid phone number! Please Enter your phone number in 07xxx-xxxxxx");
            }
            $this->phone_no = $phone_no;
        }

        public function set_email($email){
            //Trim white space
            $email = trim($email);

            //Check if email exists
            if(empty($email)){
                throw new Exception("Email can't be empty!");
            }
            //Check if email is valid
            if(!preg_match('/^[a-zA-Z0-9_]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,}$/',$email)){
                throw new Exception("Invalid Email! Please enter a proper email such as: josh123@example.com.");
            }

            $this->email = $email;
        }

        public function set_address($address){
            //Trim white space
            $address = trim($address);

            //Check if address exists
            if(empty($address)){
                throw new Exception("Address can't be empty!");
            }

            $this->address = $address;
        }

        public function set_sex($sex){
            //Trim white space
            $sex = trim($sex);

            //Check if sex exists
            if(empty($sex)){
                throw new Exception("Sex can't be empty!");
            }

            //Make sure sex is inputed correctly
            if($sex == "M" || $sex == "F" || $sex == "O"){
                throw new Exception("Sex can't be anything other than male, female or other");
            }
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
        //Connect to db using the parent class
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