<?php
$servername = "localhost";
$username = "";
$password = "!";
$db ="";
//require_once("./config/config.php");

// Connect your database here.
    $conn = mysqli_connect($servername, $username, $password, $db);
    $sql = "use table";
    mysqli_query($conn, $sql);

    
     

?> 