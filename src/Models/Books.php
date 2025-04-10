<?php
    Class Books {
        // Variable for database connection
        private $conn;

        // Properties of books
        private $pupil_id;
        private $isbn;

        // Construct function connect to the database
        public function __construct($db){
            $this->conn = $db;
        }

        // Setters for each value
        public function set_pupil_id($id){
            $query = "SELECT pupil_id from pupils";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()){
                $pupils = $stmt->fetchAll();

                foreach($pupils as $pupil){
                    if($pupil === $id){
                        $this->pupil_id = $id;
                        return true;
                    }
                }

                throw new Exception("Pupil with this id doesn't exist!", 400);
            }
        }

        public function set_isbn($isbn){
            $query = "SELECT isbn, no_of_copies FROM books";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()){
                $books = $stmt->fetchAll();

                foreach($books as $book){
                    if($book["isbn"] === $isbn && $book["no_of_copies"] > 0){
                        $this->isbn;
                        return true;
                    }
                }

                throw new Exception("This book is currently unavailable!", 400);
            }else{
                throw new Exception("Server Error!", 500);
            }
        }

        public function fetch_books(){
            $query = "SELECT * FROM books";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()){
                $books = $stmt->fetchAll();
                return $books;
            }else{
                throw new Exception("Server Error.", 500);
            }
        }

    }
?>