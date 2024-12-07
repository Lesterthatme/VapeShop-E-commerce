<?php
session_start();

require '../connection/db_conn.php';

if (isset($_SESSION['user_id'])) {
    $user_name = $_SESSION['user_name'];
    $id = $_SESSION['user_id'];
} else {
    session_unset();
    session_destroy();
    header("Location: ../pages/index.php");
}



if(isset($_POST['update-pass'])){
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $pass = validate($_POST['pass']);
    $newpass = validate($_POST['newpass']);
    $conpass = validate($_POST['conpass']);

    $id_pass = validate($_POST['id_pass']);

    if($newpass != $conpass){
        echo "<script>
                alert('Details not matched, please input again..');
                setTimeout(function() {
                    window.location = 'changepasscus.php';
                }, 1500);
            </script>";
    }else{
        $numcha = false;
        $upperCha = false;
        $specialCha = false;

        $countofpass = strlen($conpass);
        for ($i = 0; $i < $countofpass; $i++) {
            if ($conpass[$i] >= 0 && $conpass[$i] <= 9) {
                $numcha = true;
            } else if ($conpass[$i] >= 'A' && $conpass[$i] <= 'Z') {
                $upperCha = true;
            } else if ($conpass[$i] >= 'a' && $conpass[$i] <= 'z') {
                continue;
            } else {
                $specialCha = true;
            }
        }

        if ($specialCha == true && $numcha == true && $upperCha == true) { // 
            // Hash the password
            $result01 = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_pass' AND ulvl = 2");

            if($result01){
                $row = mysqli_fetch_assoc($result01);
                $hashed_password = $row['password'];

                if (!password_verify($pass, $hashed_password)) {
                    echo "<script>
                        alert('Password not matched, please input again..');
                        setTimeout(function() {
                            window.location = 'changepasscus.php';
                        }, 1500);
                    </script>";
                }else{
                    $new_hashed_password = password_hash($newpass, PASSWORD_DEFAULT);

                    $result02 = mysqli_query($conn, "UPDATE users SET password = '$new_hashed_password' WHERE id = '$id_pass'");
            
                    if($result02){
                        //For Audit Trail
                        $fullname = $row['fName'] . ' ' . $row['lName'];
                        $temptxt = $fullname . ' update password.';
                        $idd = $row['id'];
                        mysqli_query($conn, "INSERT INTO audittable VALUES ('', NOW(), '$idd', '$temptxt')");
                        
                        header('Location: profile.php');
                        exit();
                    }
                }
                
            }else{
                echo "<script>
                        alert('Something went wrong, please input again..');
                        setTimeout(function() {
                            window.location = 'changepasscus.php';
                        }, 1500);
                    </script>";
            }
        }else{
            echo "<script>
                        alert('New password not accepted, please input again..');
                        setTimeout(function() {
                            window.location = 'changepasscus.php';
                        }, 1500);
                    </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>

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

        .main {
            display: flex;
            flex-direction: column;
            margin: auto;

            align-items: center;
            
            text-align: center;


            width: 50%;
            height: 60vh;

            background-color: whitesmoke;
            border-radius: 8px;
        }

        .sec1 {
            font-size: 25px;
            font-weight: 500;
        }

        .sec2 {
            
            width: 98%;

            display: flex;

            justify-content: center;
            align-items: center;
            text-align: center;

        }

        .sec2 .sec2sec1 {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            height: 100%;
            gap: 5px;

        }

        .sec2 .sec2sec1 label {
            font-size: 40px;
            font-style: bold;
        }

        .sec2 .sec2sec1 input {
            font-size: 30px;

            padding: 2px 2px 2px 5px;
        }

        .sec2 .sec2sec1 button {
            font-size: 30px;
            border-radius: 7px;

            cursor: pointer;
        }

        .sec2sec1 button{
            margin: 20px 0px 0px 0px;
        }

    </style>
</head>

<body>
    <div class="main">

        <div class="sec1">
            <h1>Change Password</h1>
        </div>

        <div class="sec2">
            <form method="POST">

                <div class="sec2sec1">
                    <input type="hidden" value="<?=$id ?>" name="id_pass">

                    <label for="pass">Enter current password</label>
                    <input type="password" name="pass" id="pass">

                    <label for="newpass">Enter new password</label>
                    <input type="password" name="newpass" id="newpass">

                    <label for="conpass">Confirm new password</label>
                    <input type="password" name="conpass" id="conpass">

                    <button type="submit" name="update-pass">Update password</button>
                    <button type="submit" formaction="profile.php">Cancel</button>
                </div>

                
            </form>
        </div>

    </div>
</body>

</html>