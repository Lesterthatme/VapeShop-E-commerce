<?php


$servername = "127.0.0.1";
$username = "root@";
$password = "";
$dbname = "mkcl";

$conn = new mysqli($servername, $username, $password, $dbname, 3307);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
