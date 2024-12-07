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


if (isset($_GET['remove'])) {
    $productid = $_GET['remove']; //id

    $select_item = "SELECT * FROM cart WHERE productID = '$productid'";
    $selected = mysqli_query($conn, $select_item);

    $row = mysqli_fetch_assoc($selected);
    $productName = $row['name'];

    $delete_cart_item = "DELETE FROM cart WHERE productID = '$productid'";
    $result_delete = mysqli_query($conn, $delete_cart_item);


    if ($result_delete) {
        header("location:cart.php");
        exit(0);
    }
}

if (isset($_GET['delete-all'])) {
    $delete_all = "DELETE FROM cart WHERE userID = '$id'";
    $result_delete = mysqli_query($conn, $delete_all);

    if ($result_delete) {
        header("location:cart.php");
        exit(0);
    }
}

if (isset($_POST['update-btn'])) {
    $cart = $_POST['update_quantity_id'];
    $qty = $_POST['update_qty'];

    $update_quantity_result = "UPDATE cart SET quantity='$qty' WHERE product_ID='$cart'";
    $update_result = mysqli_query($conn, $update_quantity_result);

    $update_quantity_result = "UPDATE summaryoforders SET quantityProduct='$qty' WHERE productName='$cart'";
    $update_result = mysqli_query($conn, $update_quantity_result);

    if ($update_result) {
        header("location:cart.php");
        exit(0);
    }
}

if (isset($_POST['add-btn'])) {
    $name = $_POST['name'];
    $cart = $_POST['update_quantity_id'];
    $qty = $_POST['update_qty'];

    $new_quantity = $qty + 1;

    $result03 = mysqli_query($conn, "SELECT * FROM items WHERE itemID='$cart' ");
    $temp = mysqli_fetch_assoc($result03);

    if ($temp['vStock'] >= $new_quantity) {
        $update_quantity_result = "UPDATE cart SET quantityPurchase='$new_quantity' WHERE productID='$cart'";
        $update_result = mysqli_query($conn, $update_quantity_result);

        $newquery = "SELECT cart.quantityPurchase, items.vPrice FROM cart JOIN items ON cart.productID = items.itemID WHERE cart.productID = '$cart'";
        $newresult = mysqli_query($conn, $newquery);
        $newtemp = mysqli_fetch_assoc($newresult);
        $newSum = $newtemp['vPrice'] * $newtemp['quantityPurchase'];

        $update_quantity_result1 = "UPDATE cart SET sumPurchase=$newSum WHERE productID='$cart'";
        $update_result1 = mysqli_query($conn, $update_quantity_result1);

        if ($update_result) {
            header("location:cart.php");
            exit(0);
        }
    } else {
        echo '<script type="text/javascript">
                        alert("The remaining stock is not enough. Please try again.");
                        window.location.href = "../pages/cart.php";
                      </script>';
        exit();
    }
}

if (isset($_POST['less-btn'])) {
    $name = $_POST['name'];
    $cart = $_POST['update_quantity_id'];
    $qty = $_POST['update_qty'];

    $new_quantity = $qty - 1;
    if ($new_quantity <= 0) {
        header("location:cart.php");
        exit(0);
    } else {
        $update_quantity_result = "UPDATE cart SET quantityPurchase='$new_quantity' WHERE productID='$cart'";
        $update_result = mysqli_query($conn, $update_quantity_result);

        $newquery = "SELECT cart.quantityPurchase, items.vPrice FROM cart JOIN items ON cart.productID = items.itemID WHERE cart.productID = '$cart'";
        $newresult = mysqli_query($conn, $newquery);
        $newtemp = mysqli_fetch_assoc($newresult);
        $newSum = $newtemp['vPrice'] * $newtemp['quantityPurchase'];


        $update_quantity_result1 = "UPDATE cart SET sumPurchase=$newSum WHERE productID='$cart'";
        $update_result1 = mysqli_query($conn, $update_quantity_result1);

        if ($update_result) {
            header("location:cart.php");
            exit(0);
        }
    }
}


$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart items</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="../assets/css/styleInMenu.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
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

    <div class="container" style="background-color: #fff;">
        <section class="shopping-cart" style="background-color: #fff;">
            <h1 class="heading"> shopping cart</h1>

            <table>

                <?php
                $grand_total = 0;
                $select_cart = "SELECT cart.productID, items.image, items.vFlavor, items.vPrice, cart.quantityPurchase, cart.sumPurchase 
                FROM items join cart 
                ON items.itemID = cart.productID;";
                $select_result = mysqli_query($conn, $select_cart);

                if (mysqli_num_rows($select_result) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_result)) {
                ?>
                        <form method="post">
                            <tr>
                                <input type="hidden" name="cart_id" value="<?= $fetch_cart['productID']; ?>">

                                <td><img src="admin/imagesSaInventory/<?= $fetch_cart['image']; ?>" height="80" alt=""></td>

                                <td><?= $fetch_cart['vFlavor']; ?></td>

                                <td>₱<?= number_format($fetch_cart['vPrice']); ?></td>

                                <td>
                                    <form method="post">
                                        <input type="hidden" name="name" value="<?= $fetch_cart['vFlavor']; ?>">

                                        <input type="hidden" name="update_quantity_id" min="1" value="<?= $fetch_cart['productID']; ?>">

                                        <button type="submit" class="less-btn" name="less-btn">-</button>

                                        <input type="number" name="update_qty" readonly class="qty" min="1" max="99" value="<?= $fetch_cart['quantityPurchase']; ?>" maxlength="2">

                                        <button type="submit" class="add-btn" name="add-btn">+</button>
                                        </a>
                                    </form>
                                </td>
                                <td>₱<?= $sub_total = $fetch_cart['sumPurchase']; ?></td>

                                <td><a href="cart.php?remove=<?= $fetch_cart['productID']; ?>" onclick="return confirm ('remove item from cart?')" class="delete-btn"><i class="fas fa-trash"></i>remove</a></td>
                            </tr>
                        </form>
                <?php
                        $grand_total += $sub_total;
                    }
                } else {
                    echo '<p style="text-align:center; font-size: 20px; margin-bottom:1rem;" class="empty"> your cart is empty!</p>';
                }
                ?>
                <tr class="table-bottom">
                    <td colspan="4">grand total</td>
                    <td>₱<?= $grand_total; ?></td>
                    <td><a href="cart.php?delete-all" onclick="return confirm ('are you sure you want to delete all?');" class="delete-btn" name="delete-all"><i class="fas fa-trash"></i>delete all</a></td>
                </tr>

            </table>
            <div class="checkout-btn">
                <a href="checkout.php" class="btn<?= ($grand_total > 1) ? '' : 'disabled'; ?>" target="_blank">proceed to checkout</a>
            </div>
        </section>
    </div>

</body>

</html>