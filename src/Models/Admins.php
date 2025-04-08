<?php
    require_once __DIR__."/Users.php";

    Class Admin extends Employee_User{
        public function __construct($db){
            parent::__construct($db);
        }

        // Get users that haven't been approved
        public function not_approved(){
            $query = "SELECT username, 
                concat(first_name, ' ', IFNULL(middle_initial, ''),' ', last_name) as name
                FROM users
                WHERE user_type = 'employee' && 
                username NOT IN (SELECT username FROM employee);";

            
            $stmt = $this->conn->prepare($query);

            if($stmt->execute()){
                $employees = $stmt->fetchAll();
                return $employees;
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

        public function approve($user){
            // First register to employee table 
            if($user->register()){
                // After that register employees based on there role
                if($user->employee_type === "T"){
                    $user->register_teacher();
                    return "Employee Approved! Check update class to register teacher.";
                }elseif($user->employee_type === "TA"){
                    $user->register_Ta();
                    return "Employee Approved!";
                }
            }
        }
    }
?>
