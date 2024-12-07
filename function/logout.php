<?php

session_start();
require "../connection/db_conn.php";

$id = $_SESSION['user_id'] ;
$name = $_SESSION['firstName'] ;
$fullname = $_SESSION['user_name'];

$currentDateTime = date('Y-m-d H:i:s');

if (isset($_SESSION['user_id'])) {
    $temptext = $fullname . ' logged out.';
    
    $temp = "INSERT INTO audittable(dateTime, uid, description) VALUES ('$currentDateTime', '$id', '$temptext')";
    $result = mysqli_query($conn, $temp);

    if($result){
        session_unset();
        session_destroy();

        header("Location: ../pages/index.php");
        exit();
    }
    
} else {
    header("Location: ../pages/index.php");
    exit();
}

?>