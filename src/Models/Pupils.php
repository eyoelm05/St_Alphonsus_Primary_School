<?php
    // The code in this file is entirely custom made by me.
    // Please note that explanation for querying the database are explained in users model.

    class Pupil{
        // Variable for db connection
        private $conn;

        // Properties of pupils
        private $first_name;
        private $middle_initial;
        private $last_name;
        private $sex;
        private $address;
        private $date_of_birth;
        private $class_name;
        private $medicals;


        // Construct function connect to the database
        public function __construct($db){
            $this->conn = $db;
        }

        // Setters for each value
        public function set_name($first_name, $middle_initial, $last_name) {
            // Trim whitespace
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
        
            // Middle initial is optional, but if provided, must be a single alphabetic character
            if (!empty($middle_initial) && (!ctype_alpha($middle_initial) || strlen($middle_initial) > 1)) {
                throw new Exception("Middle initial must be a single letter.", 400);
            }
        
            // Assign values
            $this->first_name = $first_name;
            $this->middle_initial = $middle_initial;
            $this->last_name = $last_name;
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

        public function set_address($address){
            $address = trim($address);

            //Check if address exists
            if(empty($address)){
                throw new Exception("Address can't be empty!", 400);
            }

            $this->address = $address;
        }

        public function set_date_of_birth($date_of_birth){
            // Check if date of birth exists
            if(empty($date_of_birth)){
                throw new Exception ("Date of birth can't be empty!", 400);
            }

            // Get the current date
            $current_date = new DateTime("now");
            
            // Convert inserted date to date object
            $date_of_birth_object = date_create($date_of_birth);

            // Convert inserted date to date object
            $interval = date_diff($date_of_birth_object, $current_date);

            if($interval->format("%y") < 4){
                throw new Exception ("Your child must be at least 4 years old!", 400);
            }elseif($interval->format("%y") > 15){
                throw new Exception ("Your child must be under 15 years old");
            }
            $this->date_of_birth = $date_of_birth;
        }

        public function set_class_name($class_name){
            $class_name = trim($class_name);

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


        public function set_medicals($medicals){

            //If medicals exists it has to be an array.
            if(!empty($medicals) && !is_array($medicals)){
                throw new Exception ("Medicals must be an array", 400);
            }
            $this->medicals = $medicals;
        }

        // Check parent method
        public function check_parent($username, $id){
            // Set parent variable to false.
            $parent = false;

            // Query to retrieve pupil id
            $query = "SELECT pupil_id as id FROM pupil_parent WHERE username = :username";
            
            $stmt = $this->conn->prepare($query);
            if($stmt->execute([
                "username" => $username
            ])){
                // Fetch rows as arrays
                $rows = $stmt->fetchAll();

                // Loop over the ids from the database.
                foreach($rows as $row){
                    // Check if a value similar to the argument id exists.
                    if($row["id"] == $id){
                        $parent = true;
                    }
                }
            }else{
                throw new Exception ("Server Error!", 500);
            }

            return $parent;
        }

        // Add pupil method
        public function add_pupil($parent_username, $relationship){
            $query_pupil = "INSERT INTO pupils (first_name, middle_initial, last_name, date_of_birth, address, sex, class_name)
                            VALUES (:first_name, :middle_initial, :last_name, :date_of_birth, :address, :sex, :class_name);";

            $stmt = $this->conn->prepare($query_pupil);

            if($stmt->execute([
                "first_name" => $this->first_name,
                "middle_initial" => $this->middle_initial,
                "last_name" => $this->last_name,
                "date_of_birth" => $this->date_of_birth,
                "address" => $this->address,
                "sex" => $this->sex,
                "class_name" => $this->class_name
            ])){
                // Retrieve the id of the new pupil.
                $pupil_id = $this->conn->lastInsertId();

                // Query to add pupils
                $query_pupil_parent = "INSERT INTO pupil_parent (username, pupil_id, relationship)
                                        VALUES (:username, :pupil_id, :relationship)";
                                        
                // Handle Inserting medical info
                if($this->medicals){
                    foreach ($this->medicals as $medical_info) {
                        $query_pupil_medicals = "INSERT INTO pupil_medicals (medical_info, pupil_id) VALUES (:medical_info, :pupil_id)";
    
                        $stmt2 = $this->conn->prepare($query_pupil_medicals);
                        $stmt2->execute([
                            "medical_info" => $medical_info,
                            "pupil_id" => $pupil_id
                        ]);
                    }
    
                }

                $stmt1 = $this->conn->prepare($query_pupil_parent);

                if($stmt1->execute([
                    "username" => $parent_username,
                    "pupil_id" => $pupil_id,
                    "relationship" => $relationship
                ])){
                    return true;
                } else {
                    return false;
                }

            }else{
                throw new Exception ("Server Error!", 500);
            }
            
        }

        public function add_parent($id, $username, $relationship){
            $query_select = "SELECT count(*) AS no_pupil FROM pupils WHERE pupil_id = :id";
            $stmt1 = $this->conn->prepare($query_select);

            if($stmt1->execute(array(
                "id" => $id
            ))){
                $row = $stmt1->fetch();
                if($row["no_pupil"]){
                    $query_insert = "INSERT INTO pupil_parent(pupil_id, username, relationship)
                                    VALUES (:id, :username, :relationship)";
                    $stmt2 = $this->conn->prepare($query_insert);

                    if($stmt2->execute(array(
                        "id" => $id,
                        "username" => $username,
                        "relationship" => $relationship
                    ))){
                        return true;
                    }else{
                        throw new Exception ("Server Error!", 500);
                    }
                }else{
                    throw new Exception ("Student doesn't exist!", 400);
                }
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        public function read_single($id){
            // Query to retrieve pupil information with parents and teachers
            $query = "
                SELECT 
                p.pupil_id as id,
                c.class_name as class,
                p.first_name, 
                p.middle_initial,
                p.last_name,
                p.address,
                p.date_of_birth,
                p.sex,
                concat(pt.first_name, ' ',IFNULL(pt.middle_initial, ''),' ', pt.last_name) as teacher_name,
                GROUP_CONCAT(DISTINCT concat(ppu.first_name,' ',IFNULL(ppu.middle_initial, ''), ' ' , ppu.last_name, ' ', pp.relationship) SEPARATOR '\n') as parents,
                IFNULL(GROUP_CONCAT(DISTINCT concat(tau.first_name,' ',IFNULL(tau.middle_initial, ''), ' ' , tau.last_name) SEPARATOR ', '), '') as teacher_assistants,
                IFNULL(GROUP_CONCAT(DISTINCT pm.medical_info SEPARATOR ', '), '') as medicals,
                pb.isbn,
                pb.borrowed_date,
                pb.due_date,
                pb.date_returned,
                b.title,
                b.author
                FROM pupils p
                LEFT JOIN pupil_parent pp ON p.pupil_id = pp.pupil_id
                LEFT JOIN classes c ON p.class_name = c.class_name
                LEFT JOIN teacher ON c.teacher = teacher.username
                LEFT JOIN users pt ON teacher.username = pt.username
                LEFT JOIN users ppu ON pp.username = ppu.username
                LEFT JOIN teacher_assistant pta ON p.class_name = pta.class_name
                LEFT JOIN users tau ON pta.username = tau.username 
                LEFT JOIN pupil_medicals pm ON p.pupil_id = pm.pupil_id
                LEFT JOIN borrowed_books pb ON p.pupil_id = pb.pupil_id
                LEFT JOIN books b ON pb.isbn = b.isbn
                WHERE p.pupil_id = :id
                GROUP BY pb.isbn, pb.borrowed_date, pb.due_date, pb.date_returned, b.title, b.author
            ";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute(array(
                "id" => $id
            ))){
                $rows = $stmt->fetchAll();
                $pupil = null;

                foreach ($rows as $row) {
                    if ($pupil === null) {
                        $pupil = [
                            'id' => $row['id'],
                            'first_name' => $row['first_name'],
                            'middle_initial' => $row['middle_initial'],
                            'last_name' => $row['last_name'],
                            'class' => $row['class'],
                            'address' => $row['address'],
                            'date_of_birth' => $row['date_of_birth'],
                            'sex' => $row['sex'],
                            'teacher_name' => $row['teacher_name'],
                            'parents' => [],
                            'teacher_assistants' => explode(', ', $row['teacher_assistants']),
                            'medicals' => explode(', ', $row['medicals']),
                            'borrowed_books' => []
                        ];
                    }

                    if (!empty($row['isbn'])) {
                        $pupil['borrowed_books'][] = [
                            'isbn' => $row['isbn'],
                            'title' => $row['title'],
                            'author' => $row['author'],
                            'borrowed_date' => $row['borrowed_date'],
                            'due_date' => $row['due_date'],
                            'date_returned' => $row['date_returned']
                        ];
                    }
                }

                return $pupil;

            }else{
                throw new Exception ("Server Error!", 500);
            }
        } 

        public function read_parent($username){
            //Query to get pupils of the requesting parent
            $query = "
            SELECT
                p.pupil_id as id,
                concat(p.first_name, ' ', IFNULL(p.middle_initial, ''),' ', p.last_name) as name,
                p.class_name as current_class,
                p.date_of_birth
                FROM pupils p
                LEFT JOIN pupil_parent pp ON p.pupil_id = pp.pupil_id
                WHERE pp.username = :username
            ";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute(array(
                "username" => $username
            ))){
                $pupils = $stmt->fetchAll();
                return $pupils;
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        public function update($id){
            // Query to update user details
            $query = "UPDATE pupils SET first_name = :first_name, middle_initial = :middle_initial, last_name = :last_name, 
                        date_of_birth = :date_of_birth, address = :address, sex = :sex, class_name = :class_name
                        WHERE pupil_id = :id";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute([
                "first_name" => $this->first_name,
                "middle_initial" => $this->middle_initial,
                "last_name" => $this->last_name,
                "date_of_birth" => $this->date_of_birth,
                "address" => $this->address,
                "sex" => $this->sex,
                "class_name" => $this->class_name,
                "id" => $id
            ])){
                // Updating medical info
                // Delete all medical queries first
                $query_dm = "DELETE FROM pupil_medicals WHERE pupil_id = :id";
                $stmt_dm = $this->conn->prepare($query_dm);
                $stmt_dm->execute(["id" => $id]);
                
                // Handle Inserting medical info
                if($this->medicals){
                        foreach ($this->medicals as $medical_info) {
                            $query_pupil_medicals = "INSERT INTO pupil_medicals (medical_info, pupil_id) VALUES (:medical_info, :pupil_id)";
    
                            $stmt2 = $this->conn->prepare($query_pupil_medicals);
                            $stmt2->execute([
                                "medical_info" => $medical_info,
                                "pupil_id" => $id
                            ]);
                        }
                }

                return true;
            }else{
                return false;
            }
            
        }
    }
?>