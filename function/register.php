<?php
session_start();
require "../connection/db_conn.php";
$currentDateTime = date('Y-m-d H:i:s');


require '../PHPMailer/vendor/autoload.php'; // Ensure this line is present to autoload PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST['create'])) {
    $ulvl = $_POST['create'];

    if ($ulvl == 1) {
        $ulvlNotEmpty = true;
    }

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $u_email = validate($_POST['u_email']);
    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $u_city = validate($_POST['u_city']);
    $u_barangay = validate($_POST['u_barangay']);
    $u_street = validate($_POST['u_street']);
    $u_hnum = validate($_POST['u_hnum']);
    $u_cnum = validate($_POST['u_cnum']);

    //validation of email if it's used to another account...
    $result01 = mysqli_query($conn, "SELECT * FROM users WHERE email = '$u_email' AND email_verified_at IS NOT NULL");

    if (mysqli_num_rows($result01) > 0) {

        if ($ulvlNotEmpty = true) {
            echo '<script type="text/javascript">
            alert("Email already used.");
            window.location.href = "../pages/signup.php?ulvl=1";
          </script>';
            exit();
        } else {
            echo '<script type="text/javascript">
                        alert("Email already used.");
                        window.location.href = "../pages/signup.php?ulvl=2";
                      </script>';
            exit();
        }
    } else {



        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Enable verbose debug output
            $mail->SMTPDebug = 0; // SMTP::DEBUG_SERVER;

            //Send using SMTP
            $mail->isSMTP();

            //Set the SMTP server to send through
            $mail->Host = 'smtp.gmail.com';

            //Enable SMTP authentication
            $mail->SMTPAuth = true;

            //SMTP username
            $mail->Username = 'lesterarjaymerino.basc@gmail.com';

            //SMTP password
            $mail->Password = 'ncwn gsfj ormr vxgv'; //app password

            //Enable TLS encryption;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->Port = 587; 
            //pang email

            //Recipients
            $mail->setFrom('lesterarjaymerino.basc@gmail.com', 'MkclVapeShop');

            //Pag sesendan ng email
            $mail->addAddress($u_email, $last_name);

            //Set email format to HTML
            $mail->isHTML(true);

            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6); //ipapadala

            $mail->Subject = 'Email verification';
            $mail->Body    = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';

            $mail->send();
            // echo 'Message has been sent';

            if (
                empty($u_email) || empty($first_name) || empty($last_name) || empty($u_city) ||
                empty($u_barangay) || empty($u_street) || empty($u_hnum) || empty($u_cnum)
            ) {
                header("Location: ../pages/signup.php?error=All Fields Are Required");
                exit();
            }

            if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../pages/signup.php?error=Invalid Email Format");
                exit();
            }


            if ($ulvlNotEmpty  == true) { //admin
                $sql = "INSERT INTO users (email, verification_code, fName, lName, uCity, uBarangay, uStreet, uHnum, uCnum,ulvl, uAttempt) 
                VALUES ( '$u_email','$verification_code' ,'$first_name', '$last_name', '$u_city', '$u_barangay',
                '$u_street', '$u_hnum', '$u_cnum',1, 0)";
                $result = mysqli_query($conn, $sql);
            } else {                       //user
                $sql = "INSERT INTO users (email, verification_code, fName, lName, uCity, uBarangay, uStreet, uHnum, uCnum,ulvl, uAttempt) 
            VALUES ( '$u_email','$verification_code' ,'$first_name', '$last_name', '$u_city', '$u_barangay',
            '$u_street', '$u_hnum', '$u_cnum',2, 0)";
                $result = mysqli_query($conn, $sql);
            }


            if ($result) {
                $_SESSION['email'] = $u_email;
                $_SESSION['CODE'] = $verification_code;
                $_SESSION['name'] = $first_name . ' ' . $last_name;
                echo "<script>alert('Successfully created, check your email for verification later...');
                window.location='../pages/addPassword.php';</script>";
                exit();
            } else {
                header("Location: ../pages/signup.php?error=Failed to create account. Please try again.");
                exit();
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

if (isset($_POST['update-create'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    //for audit trail
    $oldfirstname = validate($_POST['oldfName']);
    $oldlastname = validate($_POST['oldlName']);

    //pag sasave ng picture sa local storage
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../pages/admin/imagesSaInventory/' . $image;

    $id = validate($_POST['id_acc']);
    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $u_city = validate($_POST['u_city']);
    $u_barangay = validate($_POST['u_barangay']);
    $u_street = validate($_POST['u_street']);
    $u_hnum = validate($_POST['u_hnum']);
    $u_cnum = validate($_POST['u_cnum']);

    if (empty($first_name) || empty($last_name) || empty($u_city) || empty($u_barangay) || empty($u_street) || empty($u_hnum) || empty($u_cnum)) {
        header("Location: ../pages/editsignup.php");
        exit();
    }

    $result02 = mysqli_query($conn, "UPDATE users SET image = '$image' ,fName= '$first_name', lName= '$last_name', uCity = '$u_city',
    uBarangay = '$u_barangay', uStreet= '$u_street', uHnum= '$u_hnum', uCnum = '$u_cnum' WHERE id = '$id'");

    if ($result02) {
        //For Audit Trail
        $fullname = $oldfirstname . ' ' . $oldlastname;
        $temptxt = $fullname . ' edit the details of the account';
        mysqli_query($conn, "INSERT INTO audittable VALUES ('', NOW(), '$id', '$temptxt')");

        if (!file_exists("../pages/admin/imagesSaInventory/" . $image)) {
            move_uploaded_file($image_tmp_name, $image_folder);
        }

        header("Location: ../pages/admin/adminpage.php");
        exit();
    } else {
        header("Location: ../pages/editsignup.php?error=Failed to edit the account. Please try again.");
        exit();
    }
}
