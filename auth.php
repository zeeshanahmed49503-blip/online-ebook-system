<?php
    $host = "localhost";
    $root = "root";
    $password = "";
    $dataBase = "online e-book";

    $conn = mysqli_connect($host, $root, $password, $dataBase);

    if(!$conn){
        die("Connection Failed");
    }
?>