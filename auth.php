<?php
session_set_cookie_params([
    'lifetime' => 86400 * 7,
    'path' => '/',
    'httponly' => true
]);
session_start();

    $host = "localhost";
    $root = "root";
    $password = "";
    $dataBase = "online e-book";

    $conn = mysqli_connect($host, $root, $password, $dataBase);

    if(!$conn){
        die("Connection Failed");
    }
?>