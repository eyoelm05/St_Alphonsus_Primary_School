<?php
    // The code in this file is entirely custom made by me.

    class User {
        // Variable for db connection
        protected $conn;

        // Properties of user
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

        // Connects with database when the object is instantiated.
        public function __construct($db){
            $this -> conn = $db;
        }

        // Setter functions for each value
        public function set_username($username){
            // Trim whitespace
            $username = trim($username);

            // Check if username exists
            if(empty($username)){
                // Sends error message and code.
                throw new Exception("Username can't be empty!", 400);
            }

            // Use preg_match to make sure that username should have the same pattern as the regular expression.
            if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
                throw new Exception("Invalid username! Must be 3-20 characters long and contain only letters, numbers, or underscores.", 400);
            };

            // Assign value to property
            $this->username = $username;
        }

        public function set_name($first_name, $middle_initial, $last_name) {
            $first_name = trim($first_name);
            $middle_initial = trim($middle_initial);
            $last_name = trim($last_name);
        
            // Check if first and last name are empty
            if (empty($first_name)) {
                throw new Exception("First name can't be empty!", 400);
            }
            if (empty($last_name)) {
                throw new Exception("Last name can't be empty!", 400);
            }
        
            // Ensure names contain only letters
            if (!ctype_alpha($first_name) || !ctype_alpha($last_name)) {
                throw new Exception("Names must contain only alphabetic characters.", 400);
            }
        
            // Middle initial is optional, but if provided, must be a single alphabetic character.
            if (!empty($middle_initial) && (!ctype_alpha($middle_initial) || strlen($middle_initial) > 1)) {
                throw new Exception("Middle initial must be a single letter.", 400);
            }
        
            $this->first_name = $first_name;
            $this->middle_initial = $middle_initial;
            $this->last_name = $last_name;
        }

        public function set_phone_no($phone_no){
            $phone_no = trim($phone_no);

            // Check if phone_no exists
            if(empty($phone_no)){
                throw new Exception("Phone number can't be empty!", 400);
            }

            // Check if phone_no is a valid UK number using regular expression.
            if(!preg_match('/^07\d{9}$/',$phone_no)){
                throw new Exception("Invalid phone number! Please Enter your phone number in 07xxx-xxxxxx", 400);
            }

            $this->phone_no = $phone_no;
        }

        public function set_email($email){
            $email = trim($email);

            // Check if email exists
            if(empty($email)){
                throw new Exception("Email can't be empty!", 400);
            }

            // Check if email is valid using regular expression
            if(!preg_match('/^[a-zA-Z0-9_.]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,}$/',$email)){
                throw new Exception("Invalid Email! Please enter a proper email such as: josh123@example.com.", 400);
            }

            $this->email = $email;
        }

        public function set_address($address){
            $address = trim($address);

            // Check if address exists
            if(empty($address)){
                throw new Exception("Address can't be empty!", 400);
            }

            $this->address = $address;
        }

        public function set_sex($sex){
            $sex = trim($sex);

            // Check if sex exists
            if(empty($sex)){
                throw new Exception("Sex can't be empty!", 400);
            }

            // Make sure sex is inputted correctly
            if($sex !== "M" && $sex !== "F" && $sex !== "O"){
                throw new Exception("Sex can't be anything other than male, female or other", 400);
            }

            $this->sex = $sex;
        }

        public function set_user_type($user_type){
            // Validation is not required here.
            // It has already been performed in the register endpoint.
            $this->user_type = trim($user_type);
        }
        
        // This code is a modified version of the password verification we learned in class.
        public function set_password($password){
            $password = trim($password);

            // Check if password has 8 characters
            if(strlen($password) < 8){
                throw new Exception("Password must have at least 8 characters!", 400);
            }

            // Array to check that the password contains upper case, lower case, digits and special characters
            // Why array?: to be able to loop through them and handle exceptions easily.
            $check_arr = array(
                "upper" => false,
                "lower" => false,
                "digit" => false,
                "special" => false
            ); 


            // Loop over each character in the password and check the character type.
            for($i=0; $i< strlen($password);$i++){
                $chr = $password[$i];

                if(ctype_upper($chr)){
                    $check_arr["upper"] = true;
                }
                elseif(ctype_lower($chr)){
                    $check_arr["lower"] = true;
                }
                elseif(ctype_digit($chr)){
                    $check_arr["digit"] = true;
                }
                elseif(!ctype_alnum($chr)){
                    $check_arr["special"] = true;
                }
            }

            // Create the Error string
            $err_str = "Your password must contain: ";

            // Loop over the check_arr and add value's that don't exist to the error string in a readable format.
            foreach($check_arr as $key => $value){
                if($value){
                    continue;
                }
                if($key == "upper" || $key == "lower"){
                    $err_str .= "\n".ucfirst($key). " case";
                }elseif($key == "special"){
                    $err_str .= "\n".ucfirst($key)." characters";
                }else{
                    $err_str .= "\n".ucfirst($key)."s";
                }
            }

            // Hash the password if the password has everything required. 
            // Else send the error string as an exception.
            if($check_arr["upper"] && $check_arr["lower"] && $check_arr["digit"] && $check_arr["special"]){
                $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
            }else{
                throw new Exception($err_str, 400);
            }
        }

        // Register method
        public function register(){
            // Query used to insert a user
            $query = "INSERT INTO users (username, first_name, middle_initial, last_name, email, phone_no, address, sex, user_type, password_hash) 
                    VALUES (:username, :first_name, :middle_initial, :last_name, :email, :phone_no, :address, :sex, :user_type, :password_hash)";


            // Prepare statement: Used to prepare sql query without the data.
            $stmt = $this->conn->prepare($query);

            // Execute the statement by adding it's binding parameters.
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

        // Method to check if user exists
        public function check_user(){
            $query = 'SELECT COUNT(*) AS no_user FROM users WHERE username = :username';

            $stmt = $this->conn->prepare($query);
            $stmt->execute(["username" => $this->username]);

            // Fetch the first row
            $row = $stmt->fetch();

            // Cast row returned form the query to integer
            settype($row["no_user"], "integer");

            // Checks if there is user
            if($row['no_user'] !== 0){
                return false;
            } else {
                return true;
            }    
        }

        // Method to check if user exists
        public function check_email(){
            $query = 'SELECT COUNT(*) AS no_user FROM users WHERE email = :email';

            $stmt = $this->conn->prepare($query);
            $stmt->execute(["email" => $this->email]);

            // Fetch the first row
            $row = $stmt->fetch();

            // Cast row returned form the query to integer
            settype($row["no_user"], "integer");

            // Checks if there is user
            if($row['no_user'] !== 0){
                return false;
            } else {
                return true;
            }    
        }

        // Login method
        public function login($username, $password){
            $query = "SELECT username, user_type, password_hash FROM users WHERE username = :username";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                "username" => $username
            ]);

            $row = $stmt->fetch();

            // Check if username exists and verify password
            if($row && password_verify($password, $row["password_hash"])){

                // If user type is employee make another query to retrieve employee type.
                if($row['user_type'] == "employee"){
                    $query2 = "SELECT employee_type FROM employee WHERE username = :username";
                    $stmt2 = $this->conn->prepare($query2);
                    $stmt2->execute([
                        "username" => $row["username"]
                    ]);

                    $row2 = $stmt2->fetch();
                    $row += $row2; // Concatenate the two results.
                }
                
                // Take out password hash from the array.
                unset($row["password_hash"]);

                return $row;
            }else{
                throw new Exception ("Invalid Credentials!", 401);
            }
        }

        // Profile method
        public function profile($username){
            $query = "SELECT username, first_name, middle_initial, last_name, email, phone_no, address, sex FROM users  WHERE username = :username";
            $stmt = $this->conn->prepare($query);
            if($stmt->execute(["username" =>  $username])){
                $row = $stmt->fetch();
                return $row;
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        // Update method
        public function update($username){
            $query = "UPDATE users SET first_name = :first_name, middle_initial = :middle_initial, last_name = :last_name, 
                    email = :email, phone_no = :phone_no, address = :address, sex = :sex WHERE username = :username";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute([
                "username" => $username,
                "first_name" => $this->first_name,
                "middle_initial" => $this->middle_initial,
                "last_name" => $this->last_name ,
                "email" => $this->email,
                "phone_no" => $this->phone_no,
                "address" => $this->address,
                "sex" => $this->sex,
            ])){
                return true;
            }

            return false;
        }

        // Delete method
        public function delete($username){
            $query = "DELETE FROM users WHERE username = :username";

            $stmt = $this->conn->prepare($query);
            if($stmt->execute([
                "username" => $username
            ])){
                return true;
            }

            return false;
        }
    }

    class Parent_User extends User {
        // Connect to db using the parent class
        public function __construct($db){
            parent::__construct($db);
        }

        public function register(){
            // First register user
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

            return false;
        }
    }

    Class Employee_User extends User{

        // Properties specific to employee
        protected $background_check;
        protected $date_of_birth;
        protected $employee_type;
        protected $start_date;

        public function __construct($db){
            parent::__construct($db);
        }

        // Setters for each value
        public function set_background_check($background_check){
            // Cast background check to boolean
            settype($background_check, "boolean");

            // Check if background check is a boolean.
            if(!is_bool($background_check)){
                throw new Exception ("Background check can only be true or false", 400);
            }

            $this->background_check = $background_check;
        }

        public function set_date_of_birth($date_of_birth){

            // Check if date of birth exists
            if(empty($date_of_birth)){
                throw new Exception ("Date of birth can't be empty!", 400);
            }

            $this->date_of_birth = $date_of_birth;
        }

        public function set_start_date($start_date){

            // Check if start date exists
            if(empty($start_date)){
                throw new Exception ("Start date can't be empty!", 400);
            }

            $this->start_date = $start_date;
        }

        public function set_employee_type($employee_type){
            $employee_type = trim($employee_type);

            // Check if employee type exists
            if(empty($employee_type)){
                throw new Exception ("Employee type can't be empty!", 400);
            }

            // Make sure employee type is T or TA
            if($employee_type !== "T" && $employee_type !== "TA"){
                throw new Exception ("Please enter proper employee type! (T for teacher or TA for teacher assistant)", 400);
            }

            $this->employee_type = $employee_type;
        }

        // Register method
        public function register(){

            // First register user
            if(parent::register()){
                $query = "INSERT INTO employee (username, background_check, date_of_birth, employee_type, start_date)
                             VALUES (:username, :background_check, :date_of_birth, :employee_type, :start_date)";

                $stmt = $this->conn->prepare($query);

                if($stmt->execute([
                    "username" => $this->username,
                    "background_check" => $this->background_check,
                    "date_of_birth" => $this->date_of_birth,
                    "employee_type" => $this->employee_type,
                    "start_date" => $this->start_date
                ])){
                    return true;
                }
                return false;
            }

            return false;
        }

        // Update method
        public function update($username){
            // First update user
            if(parent::update($username)){
                $query = "UPDATE employee SET background_check = :background_check, date_of_birth = :date_of_birth, start_date = :start_date
                        WHERE username = :username";

                $stmt = $this->conn->prepare($query);

                if($stmt->execute([
                    "background_check" => $this->background_check,
                    "date_of_birth" => $this->date_of_birth,
                    "start_date" => $this->start_date
                ])){
                    return true;
                }
                return false;
            }

            return false;
        }

    }

    Class Teacher extends Employee_User{
        // Calls the construct function on Employee user
        public function __construct($db){
            parent::__construct($db);
        }

        // Register method
        public function register(){
            // Calls register function on Employee User
            if(parent::register()){
                $query = "INSERT INTO teacher (username) VALUES (:username)";

                $stmt = $this->conn->prepare($query);

                if($stmt->execute([
                    "username" => $this->username
                ])){
                    return true;
                }
                return false;
            }

            return false;
        }
    }
    Class Teacher_Assistant extends Employee_User{
        private $class_name;

        public function __construct($db){
            parent::__construct($db);
        }

        // Setter for class_name
        public function set_class_name($class_name){
            if(empty($class_name)){
                throw new Exception ("Class name can't be empty", 400);
            }

            // Validate class_name
            if($class_name != "Reception Year" && 
            $class_name != "Year 1" && 
            $class_name != "Year 2" &&
            $class_name != "Year 3" &&
            $class_name != "Year 4" &&
            $class_name != "Year 5" &&
            $class_name != "Year 6"
            ){
                throw new Exception ("Enter proper class please!", 400);
            }
            
            $this->class_name = $class_name;
        }

        // Register method
        public function register(){

            // Calls register function on Employee User
            if(parent::register()){
                $query = "INSERT INTO teacher_assistant (username, class_name) VALUES (:username, :class_name)";

                $stmt = $this->conn->prepare($query);

                if($stmt->execute([
                    "username" => $this->username,
                    "class_name" => $this->class_name
                ])){
                    return true;
                }
                return false;
            }

            return false;
        }
    }

?>