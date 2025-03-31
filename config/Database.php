<?php
    // Portions of this code are adapted from:
    // Nixon, R. (2025). Learning PHP, MySQL & JavaScript. ‘O’Reilly Media, Inc.’


    // Using vlucas/phpdotenv for reading environmental variables
    // Source:https://github.com/vlucas/phpdotenv


    // autoload.php: helps the system interpret library classes such as dotenv.
    require_once __DIR__ . '/../vendor/autoload.php';
    use Dotenv\Dotenv;

    class Database {
        // DB Parameters
        private $host;
        private $db_name;
        private $username;
        private $password;
        private $conn;

        public function __construct() {
            // Initiating dotenv to read data.
            $dotenv = Dotenv::createImmutable(realpath(__DIR__ . '/../'));
            $dotenv->load();

            // Fetching secret information from .env file
            $this->host = $_ENV['DB_HOST'];
            $this->db_name = $_ENV['DB_NAME'];
            $this->username = $_ENV['DB_USER'];
            $this->password = $_ENV['DB_PASS'];
        }

        public function connect(){
            $this->conn = null;

            try{
                $dbattr = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

                // Adapted from Nixon, R. (2025). Learning PHP, MySQL & JavaScript. ‘O’Reilly Media, Inc.’
                // Options to set error and fetch modes to pdo.
                $opts   =
                [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                $this->conn = new PDO($dbattr, $this->username, $this->password, $opts);
            
            }catch (\PDOException $e) {
                // Pdo exception send message and code from database error.
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }

            // End of reference code.

            // My custom code
            return $this->conn;
        }
    }
?>