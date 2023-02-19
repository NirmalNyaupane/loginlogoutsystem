<?php
    $server="localhost";
    $user = "root";
    $password = "";
    $database = "college";

    $conn = mysqli_connect($server, $user, $password, $database);

    if(!$conn){
        die("Problem in connection");
    }
?>
