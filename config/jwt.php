<?php
    // Portion of this code is from:
    // Techiediaries.com. (2019). PHP JWT & REST API Authentication Tutorial: Login and Signup | Techiediaries. 
    // Available at: https://www.techiediaries.com/php-jwt-authentication-tutorial/

    // Using firebase/php-jwt for JWT encoding and decoding
    // Source: https://github.com/firebase/php-jwt

    // autoload.php: helps the system interpret library classes such as dotenv and jwt.
    require_once __DIR__ . '/../vendor/autoload.php';
    // Import Dotenv class
    use Dotenv\Dotenv;

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class JWT_TOKEN {
        private $secret_key;
        private $issuer = "St_Alphonsus_Primary_School";
        private $audience = "parent_and_employee_of_the_school";

        public function __construct(){
            $dotenv = Dotenv::createImmutable(realpath(__DIR__ . '/../'));
            $dotenv->load();

            // Set value of secret key from environmental variable
            $this->secret_key = $_ENV["SECRET_KEY"];
        }

        public function issue_token($username,$user_type,$employee_type = null){
            // The following code is taken from (Techiediaries.com, 2019).
            // However, some portions have been omitted, because they are not necessary for this project.
            // Omissions will be indicated as  a comment.
            $token = array(
                "iss" => $this->issuer,
                "aud" => $this->audience,
                "iat" => time(), // The time when the token is issued
                // "nbf" => $notbefore_claim, Omitted because this project doesn't need a time gap to activate the token.
                "exp" => time() + 2*60*60,// Token expires after 2hours(7,200 seconds)
                "data" => array(
                    "username" => $username,
                    "user_type" => $user_type,
                    "employee_type" => $employee_type
            ));// Data stored in the token (payload).
            // End of reference code.

            // My custom code
            // Encode data to a jwt token
            // 'HS256' this is the algorithm used to create the token
            $jwt = JWT::encode($token, $this->secret_key, 'HS256');
            return $jwt;
        }

        public function verify_token($jwt){
            try{
                // Decode JWT token
                // 'HS256' this is the algorithm used to create the token
                $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
                return (array)$decoded;
            }catch(Exception $e){
                throw new Exception ("Invalid token!", 401);
            }
        }
    }
?>