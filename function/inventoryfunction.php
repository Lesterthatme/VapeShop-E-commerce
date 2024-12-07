<?php

session_start();
require "../connection/db_conn.php";

$id = $_SESSION['user_id'];
$name = $_SESSION['firstName'];
$fullname = $_SESSION['user_name'];

if (isset($_POST['add-product'])) {


    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../pages/admin/imagesSaInventory/' . $image;

    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $expiration = $_POST['expiration'];

    $query = "INSERT INTO items(image, vFlavor, vPrice, vStock, vAdded, vExpDate) 
                VALUES ('$image', '$name', '$price' , '$stock' , NOW(), '$expiration')";
    $insert_query = mysqli_query($conn, $query);

    $result02 = mysqli_query($conn, "SELECT * FROM items WHERE vFlavor = '$name'");
    $temp = mysqli_fetch_assoc($result02);
    $tempproductID = $temp['itemID'];

    if ($insert_query) {
        //For Audit Trail
        $temptxt = $fullname . ' added new flavor of vape (' . $name . ').';
        mysqli_query($conn, "INSERT INTO audittable VALUES ('', NOW(), '$id', '$temptxt')");

        //para sa ordered
        if (mysqli_num_rows($result02) > 0) {
            mysqli_query($conn, "INSERT INTO ordered VALUES ('', '$tempproductID', 0)");
        }
    }

    if (file_exists("../pages/admin/imagesSaInventory/" . $image)) {
        header("location: ../pages/admin/inventory.php");
        exit();
    } else {
        move_uploaded_file($image_tmp_name, $image_folder);
        header("location: ../pages/admin/inventory.php");
        exit();
    }
}


if (isset($_POST['update-btn'])) {
    $new_image = $_FILES['image']['name'];
    $old_image = $_POST['oldimage'];
    $image_tmp_name = $_FILES['image']['tmp_name'];

    $id = $_POST['itemID'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $expiration = $_POST['expiration'];
    $latest_image;

    //For Audit Trail
    $temptxt = $fullname . ' update a flavor of vape (' . $name . ').';
    mysqli_query($conn, "INSERT INTO audittable VALUES ('', NOW(), '$id', '$temptxt')");



    if ($new_image != '') {
        $latest_image = $new_image;
        if (file_exists("../pages/admin/imagesSaInventory/" . $latest_image)) {
            mysqli_query($conn, "UPDATE items SET 
                image='$latest_image', vFlavor = '$name', vPrice = '$price', vStock= '$stock', vExpDate= '$expiration'
                WHERE itemID = '$id' ");
            header("location: ../pages/admin/inventory.php");
            exit();
        } else {
            move_uploaded_file($image_tmp_name, '../pages/admin/imagesSaInventory/' . $latest_image);

            mysqli_query($conn, "UPDATE items SET 
                image='$latest_image', vFlavor = '$name', vPrice = '$price', vStock= '$stock', vExpDate= '$expiration'
                WHERE itemID = '$id' ");

            header("location: ../pages/admin/inventory.php");
            exit();
        }
    } else {
        $latest_image =  $old_image;

        mysqli_query($conn, "UPDATE items SET 
                image='$latest_image', vFlavor = '$name', vPrice = '$price', vStock= '$stock', vExpDate= '$expiration'
                WHERE itemID = '$id' ");

        header("location: ../pages/admin/inventory.php");
        exit();
    }
}

if (isset($_POST['delete-btn'])) {
    $id_toDelete = $_POST['delete-btn'];

    $image = $_POST['image'];
    $name = $_POST['name'];

    $result01 = mysqli_query($conn, "DELETE FROM items WHERE itemID = '$id_toDelete'");

    mysqli_query($conn, "DELETE FROM ordered WHERE productID = '$id_toDelete'");

    if ($result01) {

        //For Audit Trail
        $temptxt = $fullname . ' delete a flavor of vape (' . $name . ').';
        mysqli_query($conn, "INSERT INTO audittable VALUES ('', NOW(), '$id', '$temptxt')");

        unlink("../pages/admin/imagesSaInventory/" . $image);
        header('Location: ../pages/admin/inventory.php');
        exit();
    }
}
