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
    exit();
}

if (isset($_POST['add-to-cart'])) {
    if ($id == '') {
        header('location:../pages/index.php');
        exit();
    } else {
        $product_ID = mysqli_real_escape_string($conn, $_POST['product_ID']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $image = mysqli_real_escape_string($conn, $_POST['image']);
        $qty = mysqli_real_escape_string($conn, $_POST['qty']);

        $intprice = intval($price);
        $intqty = intval($qty);
        $sumito = $intprice * $intqty;

        $query4 = "SELECT * FROM cart WHERE productID = '$product_ID' AND userID = '$id'";
        $result4 = mysqli_query($conn, $query4);

        if (mysqli_num_rows($result4) > 0) {
            $result03 = mysqli_query($conn, "SELECT * FROM items WHERE itemID='$product_ID' ");
            $temp = mysqli_fetch_assoc($result03);

            $query = "SELECT * FROM cart WHERE productID='$product_ID' AND userID = '$id'";
            $check_cart = mysqli_query($conn, $query);
            $pangcheck = mysqli_fetch_assoc($check_cart);

            $updateQuantityparadine = intval($pangcheck['quantityPurchase']) + $intqty;
            $updateSumparadine = intval($pangcheck['sumPurchase']) + $sumito;

            if ($temp['vStock'] >= $updateQuantityparadine) {
                $query2 = "UPDATE cart SET quantityPurchase = $updateQuantityparadine, sumPurchase = $updateSumparadine
                        WHERE productID = '$product_ID' AND userID = '$id'";
                $result2 = mysqli_query($conn, $query2);
                header("location:../pages/mkCustomer.php");
                exit();
            } else {
                echo '<script type="text/javascript">
                        alert("The remaining stock is not enough. Please try again.");
                        window.location.href = "../pages/mkCustomer.php";
                      </script>';
                exit();
            }
        } else {
            $result03 = mysqli_query($conn, "SELECT * FROM items WHERE itemID='$product_ID' ");
            $temp = mysqli_fetch_assoc($result03);

            if ($temp['vStock'] >= $intqty) {
                $insert_cart = "INSERT INTO cart (cartID, userID, productID, quantityPurchase, sumPurchase) VALUES ('', '$id', '$product_ID', $intqty, $sumito)";
                $insert_query = mysqli_query($conn, $insert_cart);
                header("location:../pages/mkCustomer.php");
                exit();
            } else {
                echo '<script type="text/javascript">
                        alert("The remaining stock is not enough. Please try again.");
                        window.location.href = "../pages/mkCustomer.php";
                      </script>';
                exit();
            }
        }
    }
}
