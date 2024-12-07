<?php
session_start();
require '../connection/db_conn.php';

$id = $_SESSION['user_id'];
$name = $_SESSION['user_name'];

if(isset($_POST['submit-btn'])){
    $result01 = mysqli_query($conn, "UPDATE users SET uAttempt = 0 WHERE id = '$id'");

    $result02 = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result02);

    if($result01){

        if($row['ulvl'] == 2){
            header('Location: mkCustomer.php');
            exit();
        }else{
            header('Location: admin/sos.php');
            exit();
        }
        
    }
}

if(isset($_POST['no-btn'])){
    $result01 = mysqli_query($conn, "UPDATE users SET uAttempt = 0 WHERE id = '$id'");

    $result02 = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result02);

    if($result01){

        if($row['ulvl'] == 2){
            header('Location: profile.php');
            exit();
        }else{
            header('Location: admin/adminpage.php');
            exit();
        }
        
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice Page</title>

    <style>
        body {
        display: flex;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        width: 100%;
        height: 100vh;
        background-image: url('../assets/images/vapeBG.jpg');
        background-repeat: repeat;
        background-position: 100px center;
        background-size: cover;
        }

        .main{
            background-color: white;
            width: 60%;
            height: 50vh;

            display: flex;
            flex-direction: column;
            margin: auto;
            justify-content: center;
            align-items: center;
            /* text-align: center; */

        }
    </style>
</head>

<body>
    <div class="main">
        <?php
            $result = mysqli_query($conn, "SELECT * FROM users WHERE id ='$id'");
            $row = mysqli_fetch_assoc($result);
        ?>
        <h2>Someone is trying to open your account <?= $row['uAttempt']?> times....</h2>
        <p>Is this you <?=$name ?> ???</p>

        <form method="POST">
            <button type="Submit" name="no-btn">No</button>
            <button type="Submit" name="submit-btn">Yes</button>
        </form>
       
    </div>
</body>

</html>