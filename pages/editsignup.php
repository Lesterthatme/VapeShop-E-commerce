<?php
require '../connection/db_conn.php';
$id = $_GET['id'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign Up</title>
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
            width: 600px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 80%;
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

        <h3>MKCL</h3>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        ?>
            <form action="../function/register.php" method="post" enctype="multipart/form-data">
                

                <label for="u_email">Email:</label>
                <input value="<?= $row['email'] ?>" type="email" id="u_email" name="u_email" placeholder="Email" readonly>


                <label for="first_name">First Name:</label>
                <input value="<?= $row['fName'] ?>" type="text" id="first_name" name="first_name" placeholder="First Name">

                <input type="hidden" name="oldfName" value="<?= $row['fName'] ?>">
                <input type="hidden" name="oldlName" value="<?= $row['lName'] ?>">
                <input type="hidden" name="id_acc" value="<?= $id ?>">

                <label for="last_name">Last Name:</label>
                <input value="<?= $row['lName'] ?>" type="text" id="last_name" name="last_name" placeholder="Last Name">

                <label for="u_city">City:</label>
                <input value="<?= $row['uCity'] ?>" type="text" id="u_city" name="u_city" placeholder="City">

                <label for="u_barangay">Barangay:</label>
                <input value="<?= $row['uBarangay'] ?>" type="text" id="u_barangay" name="u_barangay" placeholder="Barangay">

                <label for="u_street">Street:</label>
                <input value="<?= $row['uStreet'] ?>" type="text" id="u_street" name="u_street" placeholder="Street">

                <label for="u_hnum">Home Number:</label>
                <input value="<?= $row['uHnum'] ?>" type="text" id="u_hnum" name="u_hnum" placeholder="Home Number">

                <label for="u_cnum">Contact:</label>
                <input value="<?= $row['uCnum'] ?>" type="text" id="u_cnum" name="u_cnum" placeholder="Contact">

                <label for="imahe">Image:</label>
                <input type="file" id="imahe" name="image">

                <div class="button-container">
                    <button type="button" onclick="location.href='admin/adminpage.php';">Cancel</button>
                    <button type="submit" name="update-create" value="1">update details</button>
                </div>

            </form>
        <?php
        }
        ?>



    </div>
</body>

</html>