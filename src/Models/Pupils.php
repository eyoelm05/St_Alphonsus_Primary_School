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

        public function set_date_of_birth($date_of_birth){
            //Check if date of birth exists
            if(empty($date_of_birth)){
                throw new Exception ("Date of birth can't be empty!");
            }

            $this->date_of_birth = $date_of_birth;
        }

        public function set_class_name($class_name){
            //Trim white space
            $class_name = trim($class_name);

            //Validate class_name
            if($class_name != "Reception Year" && 
            $class_name != "Year 1" && 
            $class_name != "Year 2" &&
            $class_name != "Year 3" &&
            $class_name != "Year 4" &&
            $class_name != "Year 5" &&
            $class_name != "Year 6"
            ){
                throw new Exception ("Enter proper class please!");
            }

            $this->class_name = $class_name;
        }


        public function set_medicals($medicals){
            //Check if medicals is array
            if(!is_array($medicals)){
                throw new Exception ("Medicals must be an array");
            }
            $this->medicals-> $medicals;
        }

        
        public function add_pupil($parent_username, $relationship){
            $query_pupil = "INSERT INTO pupils (first_name, middle_initial, last_name, date_of_birth, address, sex, class_name)
                            VALUES (:first_name, :middle_initial, :last_name, :date_of_birth, :address, :sex, :class_name);";
            //Prepare statement
            $stmt = $this->conn->prepare($query_pupil);

            //Execute query
            if($stmt->execute([
                "first_name" => $this->first_name,
                "middle_initial" => $this->middle_initial,
                "last_name" => $this->last_name,
                "date_of_birth" => $this->date_of_birth,
                "address" => $this->address,
                "sex" => $this->sex,
                "class_name" => $this->class_name
            ])){
                //Retrieve the id of the new pupil.
                $pupil_id = $stmt->lastInsertId();

                //Query to add pupil parent relationship.
                $query_pupil_parent = "INSERT INTO pupil_parent (username, pupil_id, relationship)
                                        VALUES (:username, :pupil_id, :relationship)";

                //Prepare Query
                $stmt1 = $this->conn->prepare($query_pupil_parent);

                //Execute Query
                if($stmt1->execute([
                    "username" => $parent_username,
                    "pupil_id" => $pupil_id,
                    "relationship" => $relationship
                ])){
                    return true;
                } else {
                    throw new Exception("Failed to add relationship between parent and pupil");
                }

            }else{
                throw new Exception ("Failed to add pupil.");
            }
            
        } 
    }
?>