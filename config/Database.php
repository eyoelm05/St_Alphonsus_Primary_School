<?php
//autoload.php: helps the system interpret library classes such as dotenv.
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

class Database {
    //DB Parameters
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        //Code from: https://github.com/vlucas/phpdotenv
        $dotenv = Dotenv::createImmutable(realpath(__DIR__ . '/../'));
        $dotenv->load();

        //My code starts
        //Fetching secret information from .env file
        $this->host = $_ENV['DB_HOST'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
    }

    public function connect(){
        $this->conn = null;

        try{
            $dbattr = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

            //Code from close Learning PHP, MySQL & JavaScript, 7th Edition, Robin Nixon
            $opts   =
            [
              PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->conn = new PDO($dbattr, $this->username, $this->password, $opts);
           
        }catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        //My code
        return $this->conn;
    }
}