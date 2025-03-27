<?php
    // Headers: Taken from https://github.com/bradtraversy/php_rest_myblog
    // Allows this API to be accessed from any domain.
    header('Access-Control-Allow-Origin: *');
    // Sets the content type to JSON, meaning this API sends and receives JSON data.
    header('Content-Type: application/json');
    // Specifies the request methods that this API can receive. In this case, the API can only be accessed if the request is made with a POST method.
    header('Access-Control-Allow-Methods: POST');
    // Specifies the headers that are allowed in the request. This enables the API to accept headers like Content-Type, Access-Control-Allow-Methods, Authorization, and X-Requested-With.
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // My Code 
    // header to be able to set cookies.
    //The server allows credentials to be included in cross-origin HTTP requests.(MDN Web Docs, 2025)
    header("Access-Control-Allow-Credentials: true");

    //Code taken and modified from Learning PHP, MySQL & JavaScript, 7th Edition
    setcookie('auth', '', time() - 2592000, '/');

    echo json_encode(array(
        "message" => "Logged out successfully"
    ))
?>