<?php
session_start();
$error = ''; // Define error variable
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;

            background-image: url('../assets/images/vapeBG.jpg');
            background-repeat: repeat;   
            background-position: 100px center;
            background-size: cover;
        }

        .main-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;

        }

        .logo {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .button-container {
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #007BFF;
            color: white;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        img {
            width: 100px;
        }

    </style>
</head>
<body>
    <div class="main-container">

        <img src="../assets/images/logo.png" alt="Logo" class="logo">
        <h3>MKCL Marketplace</h3>

        <form action="../function/verify.php" method="post">
            <label for="uname">Email:</label>
            <input type="email" id="uname" name="uname" placeholder="Enter your email">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password">

            <?php if (!empty($error)): ?>
            <h4 class="error-message"><?php echo $error; ?></h4>
            <?php endif; ?>
            
            <div class="button-container">
                <button type="submit" name="login-btn">Log In</button>
                <button type="submit" formaction="signup.php?ulvl=2">Register Now</button>
            </div>
        </form>
    </div>
</body>
</html>
