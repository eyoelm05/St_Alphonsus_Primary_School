<?php
    Class Books {
        // Variable for database connection
        private $conn;

        // Properties of books
        private $pupil_id;
        private $isbn;
        private $no_of_copies;

        // Construct function connect to the database
        public function __construct($db){
            $this->conn = $db;
        }

        // Setters for each value
        public function set_pupil_id($id){
            $query = "SELECT COUNT(*) as no_pupil FROM pupils WHERE pupil_id = :id";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute([
                "id" => $id
            ])){
                $no_pupils = $stmt->fetch();

                if($no_pupils["no_pupil"] > 0){
                    $this->pupil_id = $id;
                    return true;
                }
                throw new Exception("Pupil with this id doesn't exist!", 400);
            }
        }

        public function set_isbn($isbn){
            $query = "SELECT COUNT(*) as available_copies FROM books WHERE isbn = :isbn AND no_of_copies > 0";
        
            $stmt = $this->conn->prepare($query);
        
            if($stmt->execute([
                "isbn" => $isbn
            ])){
                $book = $stmt->fetch();
        
                if ($book && $book['available_copies'] > 0) {
                    $this->isbn = $isbn;
                    $this->no_of_copies = $book['available_copies'];
                    return true;
                } else {
                    throw new Exception("This book is currently unavailable!", 400);
                }
            } else {
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

        public function borrow_books(){
            $query = "INSERT INTO borrowed_books(isbn, pupil_id, borrowed_date) VALUES (:isbn, :id, :borrowed_date)";

            $stmt = $this->conn->prepare($query);

            $date = new DateTime("now");
            $date_string = date_format($date, 'Y-m-d');

            if($stmt->execute([
                "isbn" => $this->isbn,
                "id" => $this->pupil_id,
                "borrowed_date" => $date_string
            ])){
                $query1 = "UPDATE books SET no_of_copies = :copies
                        WHERE isbn = :isbn";
                
                $copies = $this->no_of_copies - 1;

                $stmt1 =  $this->conn->prepare($query1);

                if($stmt1->execute([
                    "copies" => $copies,
                    "isbn" => $this->isbn
                ])){
                    return true;
                }else{
                    throw new Exception ("Server Error!", 500);
                }
            }else{
                throw new Exception ("Server Error!", 500);
            }
        }

    }
?>