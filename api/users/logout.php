<?php
    // Portions of this code are adapted from:
    // Traversy, B. (2019) 'PHP REST API - MyBlog', GitHub. Available at: 
    // https://github.com/bradtraversy/php_rest_myblog.
    // And
    // Nixon, R. (2025). Learning PHP, MySQL & JavaScript. ‘O’Reilly Media, Inc.’

    // Header details explained in users/register.php
    // Adapted from Traversy, B. (2019) 'PHP REST API - MyBlog'
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    // End of external code.

    // My Custom Code 
    // Header to be able to set cookies. Details explained in users/login.php
    header("Access-Control-Allow-Credentials: true");
    // End of my custom code.

    // Adapted from Nixon, R. (2025). Learning PHP, MySQL & JavaScript. ‘O’Reilly Media, Inc.’
    // Destroy the cookie by changing it's expire date and value.
    setcookie('auth', '', time() - 2592000, '/');
    // End of external code.

    // My custom code.
    http_response_code(200);
    echo json_encode(array(
        "message" => "Logged out successfully"
    ))
?>