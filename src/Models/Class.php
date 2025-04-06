<?php
    Class Classes {
        // Properties of a class
        private $class_capacity;
        private $teacher;
        private $teacher_assistants;

        // Construct function connect to the database
        public function __construct($db){
            $this->conn = $db;
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
                throw new Exception ("Admins must have class name to access a class");
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

    }
?>