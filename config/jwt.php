<?php
    //autoload.php: helps the system interpret library classes such as dotenv and jwt.
    require "../vendor/autoload.php";
    //Import Dotenv class
    use Dotenv\Dotenv;
    //Import JWT class
    use \Firebase\JWT\JWT;

    class JWT_TOKEN {
        private $secret_key;
        private static $issuer = "St_Alphonsus_Primary_School";
        private static $audience = "parent_and_employee_of_the_school";

        public function __construct(){
            //Code from: https://github.com/vlucas/phpdotenv
            $dotenv = Dotenv::createImmutable(realpath(__DIR__ . '/../'));
            $dotenv->load();

            //My code
            //Set value of secret key from environmental variable
            $this->secret_key = $_ENV["SECRET_KEY"];
        }

        public function issue_token($username,$user_type,$employee_type = null){
            //The following code is taken from https://www.techiediaries.com/php-jwt-authentication-tutorial.
            //However, some portions have been omitted, because they are not necessary for this project.
            //Omissions will be indicated with a comment.
            $token = array(
                "iss" => self::issuer,
                "aud" => self::audience,
                "iat" => time(), //The time when the token is issued
                //"nbf" => $notbefore_claim, Omitted because this project doesn't need a time gap to activate the token.
                "exp" => time() + 2*60*60,//Token expires after 2hours(7,200 seconds)
                "data" => array(
                    "username" => $username,
                    "user_type" => $user_type,
                    "employee_type" => $employee_type
            ));//Data stored in the token.

            //Encode data to a jwt token
            //array('HS256') this is the algorithm used to create the token
            $jwt = JWT::encode($token, $this->secret_key, array('HS256'));
            return $jwt;
        }

        public function verify_token($jwt){
            try{
                //Decode JWT token
                //array('HS256') this is the algorithm used to create the token
                $decoded = JWT::decode($jwt, $this->secret_key, array('HS256'));
                return $decoded;
            }catch(Exception $e){
                return null;
            }
        }
    }
?>