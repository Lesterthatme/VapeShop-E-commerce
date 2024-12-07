<?php
require '../../connection/db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet" href="../../assets/css/adminpage.css">
</head>

<body>
    <?php include 'sidebar.php' ?>

    <div class="container">
        <section class="accounts">

            <h1 class="heading">admins account</h1>
            <center>
                <div class="box">
                    <p>REGISTER NEW ADMIN</p>
                    <a href="../signup.php?ulvl=1" class="option-btn">register</a>
                </div>
            </center>

            <div class="box-container">
                <?php
                $select_account = "SELECT * FROM users WHERE ulvl = 1";
                $select_result = mysqli_query($conn, $select_account);
                if (mysqli_num_rows($select_result) > 0) {
                    while ($fetch_accounts = mysqli_fetch_assoc($select_result)) {
                ?>
                        <div class="box">
                            <p> admin id : <span><?= $fetch_accounts['id']; ?></span> </p>
                            <p> name: <span><?= $fetch_accounts['fName']. ' ' . $fetch_accounts['lName']; ?></span></p>
                            <p> e-mail : <span><?= $fetch_accounts['email']; ?></span> </p>
                            <div class="flex-btn">
                                <a href="../editsignup.php?id=<?= $fetch_accounts['id']?>" class="option-btn">update-details</a>
                                <a href="addPasswordAdmin.php?id=<?= $fetch_accounts['id']?>" class="option-btn">edit-password</a>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="empty">no accounts available</p>';
                }
                ?>

            </div>
        </section>
    </div>
</body>

</html>