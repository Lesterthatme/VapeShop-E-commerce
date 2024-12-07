<?php
    session_start();

    require '../connection/db_conn.php';

    $email = $_SESSION['email'];
    $code  = $_SESSION['CODE'];
    $nameofUser = $_SESSION['name'];

    $error = isset($_GET['error']) ? $_GET['error'] : '';

    if(isset($_POST['check-passcode'])){

        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $passcode = validate($_POST['passcode']);

        if($code == $passcode){
            $result = mysqli_query($conn, "UPDATE users SET email_verified_at= NOW()
            WHERE email = '$email' AND verification_code = '$code'");

            // Insert audit trail after successful login
            $result01 = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND verification_code = '$code'");
            $row = mysqli_fetch_assoc($result01);
            $tempUid = intval($row['id']);

            $tempDescription = $nameofUser . ' Registered.';
            $sql1 = "INSERT INTO audittable(dateTime, uid, description)
                        VALUES( NOW(), $tempUid, '$tempDescription')";
            $result1 = mysqli_query($conn, $sql1);

            session_unset();
            session_destroy();

            echo "<script>alert('Account Verified!'); window.location='../pages/index.php';</script>";
            exit();
        }else{
            header("Location: ../pages/verifyEmail.php?error=Code did not match.");
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

        body{

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

        .sec1{
            font-size: 20px;
            font-weight: 500;
        }

        .sec2{
            

            width: 98%;

            display: flex;

            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .sec2 .sec2sec1{
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            height: 15vh;
            padding: 20px;
            gap: 5px;
        }

        .sec2 .sec2sec1 label{
            font-size: 40px;
            font-style: bold;
        }
        .sec2 .sec2sec1 input{
            font-size: 30px;

            padding: 2px 2px 2px 5px;
        }
        .sec2 .sec2sec1 button{
            font-size: 30px;
            border-radius: 7px;

            cursor: pointer;
        }

        .sec2sec2{
            display: flex;
            flex-direction: column;
            width: 100%;

        }
        .sec2sec2 .sec2sec2sec1{
            font-size: 20px;
            height: 5vh;
            width: 100%;

            color: darkred;
        }
    </style>
</head>
<body>
    <div class="main">

        <div class="sec1">
            <h1>Verify your account..</h1>
        </div>

        <div class="sec2">
            <form method="POST">

                <div class="sec2sec1">
                    <label for="pass">Enter the code</label>
                    <input type="text" name="passcode" id="pass">
                    <button type="submit" name="check-passcode">Submit</button>
                </div>

                <div class="sec2sec2">
                    <div class="sec2sec2sec1">
                        <?php if ($error): ?>
                        <h4 class="error-message"><?php echo $error; ?></h4>
                        <?php endif; ?>
                    </div>
                    
                </div>
            </form>
        </div>

    </div>
</body>
</html>