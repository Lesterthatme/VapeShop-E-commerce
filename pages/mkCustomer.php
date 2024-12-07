<?php
session_start();
require '../connection/db_conn.php';

if (isset($_SESSION['user_id'])) {
    $user_name = $_SESSION['user_name'];
    $id = $_SESSION['user_id'];
} else {
    session_unset();
    session_destroy();
    header("Location: index.php");
}

$currentDateTime = date('Y-m-d H:i:s');

if (isset($_POST['logoutbtn'])) {

    $name = $_SESSION['user_name'] . ' logged out.';

    $sql1 = "INSERT INTO audittable(dateTime, uid, description)
                    values('$currentDateTime',  $id , '$name')";
    $result1 = mysqli_query($conn, $sql1);

    header('Location: ../function/logout.php');
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cashier</title>
    <link rel="stylesheet" href="style.css">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/styleInMenu.css">
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

    <section class="products" style="background-color: #fff;">

        <h1 class="title" style="padding-top: 7rem;">Vape products</h1>

        <div class="box-container">

            <?php
            $query = "SELECT * FROM items WHERE vStock != 0 AND vExpDate >= NOW()";
            $select_products = mysqli_query($conn, $query);

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_product = mysqli_fetch_assoc($select_products)) {
            ?>
                    <form action="../function/addtocart.php" method="post">
                        <div class="box">

                            <img src="/MKCL/pages/admin/imagesSaInventory/<?= $fetch_product['image']; ?>" alt="">

                            <h3 class="name"><?= $fetch_product['vFlavor']; ?></h3>

                            <input type="hidden" name="product_ID" value="<?= $fetch_product['itemID']; ?>">

                            <input type="hidden" name="name" value="<?= $fetch_product['vFlavor']; ?>">

                            <input type="hidden" name="price" value="<?= $fetch_product['vPrice']; ?>">

                            <input type="hidden" name="image" value="<?= $fetch_product['image']; ?>">

                            <div class="flex">
                                <div class="price">₱<?= $fetch_product['vPrice']; ?></div>
                                <input type="number" name="qty" class="qty" min="1" max="99" value="1" onkeypress="if(this.value.length == 0) return false;" />
                            </div>
                            <div class="stocks" style="font-size: 20px;">
                                <h1>Quantity left: <span style="color:red"><?= $fetch_product['vStock']; ?></span></h1>
                            </div>

                            <input type="submit" class="add-to-cart" name="add-to-cart" value="add to cart">
                        </div>
                    </form>
            <?php
                }
            } else {
                echo '<p class="empty">no products added yet!</p>';
            }
            ?>

        </div>

    </section>


</body>

</html>