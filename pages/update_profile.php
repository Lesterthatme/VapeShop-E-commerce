<?php
require '../connection/db_conn.php';

session_start();
if (isset($_SESSION['user_id'])) {
    $user_name = $_SESSION['user_name'];
    $id = $_SESSION['user_id'];
} else {
    session_unset();
    session_destroy();
    header("Location: ../pages/index.php");
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>update-details</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <link rel="stylesheet" href="../assets/css/styleInMenu.css">
    <link rel="stylesheet" href="../assets/css/register.css" />
</head>

<body>

    <header>
        <a href="" class="logo"> <img src="../assets/images/logo.png" alt="">
            <b>MKCL VAPESHOP</b></a>

        <div class="icons">
            <a href="mkCustomer.php" class="fa-solid fa-house"></a>
            <a href="cart.php" class="fas fa-shopping-cart"></a>
            <a href="profile.php" class="fas fa-user-circle"></a>
        </div>
    </header>

    <section class="form-container" style="background-color: #fff;">
        <?php
        if (isset($_GET['edit-btn'])) {
            $user_ID = mysqli_real_escape_string($conn, $_GET['edit-btn']);
            $query = "SELECT * FROM users WHERE id='$id'";
            $run_query = mysqli_query($conn, $query);

            if (mysqli_num_rows($run_query) > 0) {
                $row = mysqli_fetch_array($run_query);
        ?>

                <form action="../function/editprofile.php" method="post" enctype="multipart/form-data">
                    <h3>update profile</h3>

                    <input type="hidden" id="user_ID" name="user_ID" value="<?= $row['id']; ?> ">

                    <input type="file" name="image" accept="image/png, image/jpg, image/jpeg, image/avif, image/webp" class="box" required>

                    <!-- <input type="text" name="name" required placeholder="Enter your fullname" class="box" maxlength="50" value="<?= $row['name']; ?> " /> -->

                    <input type="email" name="email" required placeholder="Enter your email" readonly class="box" maxlength="50" value="<?= $row['email']; ?>" />

                    <!-- <input type="text" name="address" required placeholder="Enter your address" class="box" maxlength="50" value="<?= $row['address']; ?>" /> -->

                    <input type="number" name="contact" id="contact" required placeholder="Contact: +63 -" class="box" maxlength="50" value="<?= $row['uCnum']; ?>" />

                    <input type="submit" value="edit profile" class="btn" name="edit-profile" />
                </form>
        <?php
            };
        };
        ?>
    </section>

</body>

</html>