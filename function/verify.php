<?php
session_start();
require '../connection/db_conn.php';


$currentDateTime = date('Y-m-d H:i:s');

if (isset($_POST['login-btn'])) {

    $email = mysqli_real_escape_string($conn, $_POST['uname']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $sqlFetch = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sqlFetch);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_pass = $row['password'];

        if (password_verify($pass, $hashed_pass)) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fName'] . ' ' . $row['lName'];
            $_SESSION['firstName'] = $row['fName'];

            // Insert audit trail after successful login
            $tempDescription = $row['fName'] . ' ' . $row['lName'] . ' logged-in.';
            $tempUid = intval($row['id']);
            $sql1 = "INSERT INTO audittable(dateTime, uid, description)
                        VALUES('$currentDateTime', $tempUid, '$tempDescription')";
            $result1 = mysqli_query($conn, $sql1);

            // Redirect based on user level
            if($row['uAttempt'] == 0){
                if ($row['ulvl'] == 1) {
                    header("Location: ../pages/admin/sos.php");
                    exit();
                } else if ($row['ulvl'] == 2) {
                    header("Location: ../pages/mkCustomer.php");
                    exit();
                }
            }else{
                header("Location: ../pages/noticePage.php");
                exit();
            }
            
        } else {
            $result01 = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
            $temp = mysqli_fetch_assoc($result01);

            $tempattempt = intval($temp['uAttempt']) + 1;

            $result02 = mysqli_query($conn, "UPDATE users SET uAttempt = $tempattempt WHERE email = '$email'");
            
            echo '<script type="text/javascript">
                        alert("Incorrect password. Please try again.");
                        window.location.href = "../pages/index.php";
                        </script>';
        }
    } else {
        echo '<script type="text/javascript">
                    alert("Incorrect email or password. Please try again.");
                    window.location.href = "../pages/index.php";
                    </script>';
    }
}
