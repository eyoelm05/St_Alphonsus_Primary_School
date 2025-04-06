<?php
    Class Classes {
        // Properties of a class
        private $class_capacity;
        private $teacher;

        // Construct function connect to the database
        public function __construct($db){
            $this->conn = $db;
        }

        // Setter functions
        public function set_class_capacity($class_capacity){
            if(empty($class_capacity) && !ctype_number($class_capacity)){
                throw new Exception ("Class capacity can't be empty and must be a number", 400);
            }

            $this->class_capacity = $class_capacity;
        }

        public function set_teacher($username, $class_name){
            if(empty($username)){
                throw new Exception ("Teacher can't be empty");
            }
            $query1 = "SELECT teacher FROM classes WHERE class_name = :class_name";
            $stmt1 = $this->conn->prepare($query1);

            if($stmt1->execute(["class_name" => $class_name])){
                $data = $stmt1->fetch();
                if($data["teacher"] === $username){
                    $this->teacher = $username;
                    return true;
                }
            }else{
                throw new Exception ("Server Error!", 500);
            }

            // Query to check if teacher is registered and not assigned to a class
            $query = "SELECT username FROM teacher t 
                    WHERE t.username = :username
                    && NOT EXISTS (
                        SELECT 1 
                        FROM classes c
                        WHERE c.teacher = t.username
                    )";

            $stmt = $this->conn->prepare($query);
            if($stmt->execute(["username" => $username])){
                $available_teachers = $stmt->fetchAll();

                foreach($available_teachers as $teacher){
                    if($username === $teacher["username"]){
                        $this->teacher = $username;
                        return true;
                    }
                }
                throw new Exception ("Teacher not registered or have already been assigned to a class", 400);
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        public function all_classes(){
            $query = "SELECT c.class_name, c.class_capacity, 
                    concat(tu.first_name, ' ', IFNULL(tu.middle_initial, ''),' ', tu.last_name) as teacher_name  
                    FROM classes c
                    LEFT JOIN users tu ON c.teacher = tu.username";
            
            $stmt = $this->conn->prepare($query);

            if($stmt->execute()){
                $classes = $stmt->fetchAll();
                return $classes;
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        // Class name for the current teacher
        public function class_teacher($username, $user_type){
            if($user_type == "T"){
                $query = "SELECT class_name FROM Classes WHERE teacher = :username";

                $stmt = $this->conn->prepare($query);
    
                if($stmt->execute(["username" => $username])){
                    $class = $stmt->fetch();
                    return $class;
                }else{
                    throw new Exception ("Server Error!", 500);
                }
            }elseif ($user_type == "TA"){
                $query = "SELECT class_name FROM teacher_assistants  WHERE username = :username";

                $stmt = $this->conn->prepare($query);
    
                if($stmt->execute(["username" => $username])){
                    $class = $stmt->fetch();
                    return $class;
                }else{
                    throw new Exception ("Server Error!", 500);
                }
            }else{
                throw new Exception ("Admins must have class name to access a class", 400);
            }
        }

        // Get pupils in a single class
        public function read_class($class_name){
            // Query to get pupils in a single class
            $query = "
                SELECT
                p.pupil_id as id,
                concat(p.first_name, ' ', IFNULL(p.middle_initial, ''),' ', p.last_name) as name,
                p.class_name as current_class
                FROM pupils p
                WHERE p.class_name = :class_name
            ";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute(array(
                "class_name" => $class_name
            ))){
                $pupils = $stmt->fetchAll();
                return $pupils;
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        public function read_single_class($class_name){
            $query = "SELECT c.class_name, c.class_capacity, 
                    concat(tu.first_name, ' ', IFNULL(tu.middle_initial, ''),' ', tu.last_name) as teacher_name  
                    FROM classes c
                    LEFT JOIN users tu ON c.teacher = tu.username
                    WHERE c.class_name = :class_name";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute(array(
                "class_name" => $class_name
            ))){
                $class = $stmt->fetch();
                return $class;
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        public function update($class_name){
            $query = "UPDATE classes SET class_capacity = :class_capacity, teacher = :teacher
            WHERE class_name = :class_name";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute(array(
                "class_capacity" => $this->class_capacity,
                "teacher" => $this->teacher,
                "class_name" => $class_name
            ))){
                return true;
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

    }
?>