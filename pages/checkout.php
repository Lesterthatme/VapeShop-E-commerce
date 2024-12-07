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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styleInMenu.css">
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <header class="no-print">
        <a href="" class="logo"> <img src="../assets/images/logo.png" alt="">
            <b>MKCL VAPESHOP</b></a>

        <div class="icons">
            <a href="mkCustomer.php" class="fa-solid fa-house"></a>
            <a href="cart.php" class="fas fa-shopping-cart"></a>
            <a href="profile.php" class="fas fa-user-circle"></a>
        </div>
    </header>

    <section class="checkout" style="background-color: #fff;">
        <form action="../function/checkoutFunc.php" method="post">
            <h3 class="title">order summary</h3>
            <div class="cart-items">
                <?php
                $grand_total = 0;
                $select_cart = "SELECT cart.sumPurchase, cart.userID, cart.quantityPurchase, items.vFlavor, items.vPrice FROM cart JOIN items ON items.itemID = cart.productID WHERE userID = '$id'";
                $select_result = mysqli_query($conn, $select_cart);

                if (mysqli_num_rows($select_result) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_result)) {
                        $grand_total += ($fetch_cart['sumPurchase']);
                        $user_ID = $fetch_cart['userID'];
                        $productName = $fetch_cart['vFlavor'];
                        $quantityProduct = $fetch_cart['quantityPurchase'];
                        $pRice = $fetch_cart['vPrice'];
                        $sum = $fetch_cart['sumPurchase'];
                ?>
                        <p><span class="name"><?= $fetch_cart['vFlavor']; ?></span><span class="price">$<?= $fetch_cart['vPrice']; ?> x <?= $fetch_cart['quantityPurchase']; ?></span></p>
                <?php
                    }
                } else {
                    echo '<p class="empty">your cart is empty</p>';
                }
                ?>
                <div class="grand-total">
                    <p class="name">grand total :<span class="price">₱<?= $grand_total; ?></span></p>
                </div>
                <a href="cart.php" class="btn">view cart</a>
            </div>

            <div class="user-info">
                <h3>Store info</h3>
                <?php
                $query = "SELECT * FROM users WHERE id='$id'";
                $select_profile = mysqli_query($conn, $query);

                if (mysqli_num_rows($select_profile) > 0) {
                    while ($fetch_profile = mysqli_fetch_assoc($select_profile)) {
                        $tempTxt = $fetch_profile['fName'] . ' ' . $fetch_profile['lName'];
                ?>
                        <p><i class="fa-solid fa-shop"></i><span><span><?= "MKCL VAPESHOP"; ?></span></span></p>
                        <p><i class="fas fa-user"></i><span><span><?= $tempTxt; ?></span></span></p>
                        <p><i class="fas fa-phone"></i><span>0929 754 5279</span></p>
                        <p><i class="fas fa-envelope"></i><span>imken28@gmail.com</span></p>
                        <p><i class="fas fa-map-marker-alt"></i><span>8 verde street, lapnit, San Ildefonso, Philippines</span></p>
                <?php
                    }
                }
                ?>
                <p name="method" class="select-box">Payment: E-Money</p>

                <button type="button" onclick="window.print();" class="btn no-print" style="background-color: green; width:100%; font-size:4rem; padding: 0.5rem;">Print</button>
                <input type="submit" value="Done order" class="btn no-print" name="submit" style="background-color: red; width:100%; font-size:4rem; padding: 0.5rem;">
            </div>
        </form>
    </section>

</body>

</html>