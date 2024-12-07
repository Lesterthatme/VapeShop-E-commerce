<?php
session_start();

require '../connection/db_conn.php';

$email = $_SESSION['email'];
$code  = $_SESSION['CODE'];


if (isset($_POST['check-pass'])) {

    //MGA NILAGAY NI USER

    $pass = $_POST['pass'];
    $countofpass = strlen($pass);

    if ($countofpass >= 10) {
        
        $specialCha = false;
        $numcha = false;
        $upperCha = false;

        for ($i = 0; $i < $countofpass; $i++) {
            if ($pass[$i] >= 0 && $pass[$i] <= 9) {
                $numcha = true;
            } else if ($pass[$i] >= 'A' && $pass[$i] <= 'Z') {
                $upperCha = true;
            } else if ($pass[$i] >= 'a' && $pass[$i] <= 'z') {
                continue;
            } else {
                $specialCha = true;
            }
        }

        if ($specialCha == true && $numcha == true && $upperCha == true) { // if correct i-ha-hash password na
            // Hash the password
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = '$hashed_password'
                        WHERE email = '$email' AND verification_code = '$code'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "<script>alert('Password Successfully created!'); 
                window.location='../pages/verifyEmail.php';</script>";
                exit();
            }
        } else {
            header("Location: ../pages/addPassword.php?error=Please follow the instruction below.");
            exit();
        }
    } else {
        header("Location: ../pages/addPassword.php?error=Please atleast 10 character password.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add password</title>

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
            justify-content: center;
            text-align: center;


            width: 50%;
            height: 50vh;

            background-color: whitesmoke;
            border-radius: 7px;
        }

        .sec1 {
            font-size: 18px;
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
            height: 15vh;
            padding: 20px;
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

        .sec2sec2 {
            display: flex;
            flex-direction: column;
            width: 100%;

        }

        .sec2sec2 .sec2sec2sec1 {
            font-size: 20px;
            height: 5vh;
            width: 100%;

            color: darkred;
        }

        .sec2sec2 .sec2sec2sec2 ul li {
            list-style-type: none;
        }

        .sec2sec2 .sec2sec2sec2 ul {
            display: flex;
            flex-direction: row;
            width: 100%;

            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="main">

        <div class="sec1">
            <h1>Last few steps before having your account..</h1>
        </div>

        <div class="sec2">
            <form method="POST">

                <div class="sec2sec1">
                    <label for="pass">Enter a password</label>
                    <input type="password" name="pass" id="pass">
                    <button type="submit" name="check-pass">Check</button>
                </div>

                <div class="sec2sec2">
                    <div class="sec2sec2sec1">
                      
                    </div>
                    <div class="sec2sec2sec2">
                        <ul>
                            <li>Minimum of 10 characters</li>
                            <li>Atleast one must a special character</li>
                        </ul>
                    </div>
                    <div class="sec2sec2sec2">
                        <ul>
                            <li>Atleast one must a number</li>
                            <li>Atleast one is an uppercase</li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>

    </div>
</body>

</html>