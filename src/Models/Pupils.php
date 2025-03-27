<?php
    class Pupils{
        //Create connection property
        private $conn;

        //Properties of pupils
        private $first_name;
        private $middle_initial;
        private $last_name;
        private $sex;
        private $address;
        private $date_of_birth;
        private $class_name;
        private $medicals;


        //Construct function to use the database
        public function __construct($db){
            $this->conn = $db;
        }

        public function set_name($first_name, $middle_initial, $last_name) {
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

        public function set_sex($sex){
            //Trim white space
            $sex = trim($sex);

            //Check if sex exists
            if(empty($sex)){
                throw new Exception("Sex can't be empty!");
            }

            //Make sure sex is inputted correctly
            if($sex !== "M" && $sex !== "F" && $sex !== "O"){
                throw new Exception("Sex can't be anything other than male, female or other");
            }

            $this->sex = $sex;
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
    }
?>