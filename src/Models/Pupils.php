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

        
        //Construct function to use the database
        public function __construct($db){
            $this->conn = $db;
        }
    }
?>