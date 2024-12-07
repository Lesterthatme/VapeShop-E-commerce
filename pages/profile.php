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

// if (isset($_GET['delete'])) {
//     $user_ID = $_GET['delete'];
//     $query = "DELETE FROM users WHERE user_ID = '$user_ID'";
//     $result = mysqli_query($conn, $query);

//     header("location:../pages/login.php");
//     exit(0);
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/styleInMenu.css">
    <link rel="stylesheet" href="../assets/css/profile.css">

</head>

<body>

    <!-- header section starts  -->
    <header>
        <a href="" class="logo"> <img src="../assets/images/logo.png" alt="">
            <b>MKCL VAPESHOP</b></a>

        <div class="icons">
            <a href="mkCustomer.php" class="fa-solid fa-house"></a>
            <a href="cart.php" class="fas fa-shopping-cart"></a>
            <a href="profile.php" class="fas fa-user-circle"></a>
        </div>
    </header>
    <!-- header section ends -->

    <section class="user-details" style="background-color: #fff;">

        <div class="user">
            <?php
            $query = "SELECT * FROM users WHERE id='$id'";
            $select_profile = mysqli_query($conn, $query);

            if (mysqli_num_rows($select_profile) > 0) {
                while ($fetch_profile = mysqli_fetch_assoc($select_profile)) {

                    $tempName = $fetch_profile['fName'] . ' ' . $fetch_profile['lName'];
                    $tempAddress =  $fetch_profile['uHnum'].' '.$fetch_profile['uStreet'].'., '. $fetch_profile['uBarangay'] . ' '. $fetch_profile['uCity'];
            ?>

                    <h3>user profile</h3>
                    <input type="hidden" name="user_ID" value="<?= $fetch_profile['id']; ?>">

                    <p class="imahe"><img src="admin/imagesSaInventory/<?=$fetch_profile['image']?>" alt="img" height="130px" style=" border-radius: 50%;"></p>

                    <p><i class="fas fa-user"></i>Name: <span><?= $tempName; ?></span></p>

                    <p><i class="fas fa-phone"></i>Contact: <span>+63<?= $fetch_profile['uCnum']; ?></span></p>

                    <p style="font-size: 26px;"><i class="fas fa-envelope"></i>Email: <span><?= $fetch_profile['email']; ?></span></p>

                    <p class="address"><i class="fas fa-map-marker-alt"></i>Address: <span><?= $tempAddress; ?></span></p>
                    <center>

                        <div class="account">
                            <a href="changepasscus.php" class="delete-btn" >change password</a>
                            <a href="update_profile.php?edit-btn=<?=$id ?>" class="btn">update info</a>
                            <a href="../function/logout.php" onclick="return confirm('logout from this website?');" class="logout-btn">logout</a>
                            <br>
                            <!-- <a href="profile.php?delete=<?= $fetch_profile['user_ID']; ?>" onclick="return confirm ('are you sure you want to delete this account?')" class="delete-btn">delete profile</a> -->
                        </div>
                    </center>

            <?php
                }
            }
            ?>
        </div>

    </section>

</body>

</html>