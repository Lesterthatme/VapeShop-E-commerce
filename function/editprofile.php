<?php

require '../connection/db_conn.php';

if (isset($_POST['edit-profile'])) {
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../pages/admin/imagesSaInventory/'. $image;
    


    $user_ID = mysqli_real_escape_string($conn, $_POST['user_ID']);

    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    
    if (file_exists("../pages/admin/imagesSaInventory/" . $image)) {
        $query = "UPDATE users SET image = '$image', uCnum = '$contact'
        WHERE id = '$user_ID'";
        $result = mysqli_query($conn, $query);
    }else{
        move_uploaded_file($image_tmp_name, $image_folder);

        $query = "UPDATE users SET image = '$image', uCnum = '$contact'
        WHERE id = '$user_ID'";
        $result = mysqli_query($conn, $query);
    }
    

    if ($result) {
        header("Location:../pages/profile.php");
        exit();
    }
} 
// else {
//     header("Location: ../user/update_profile.php");
//     exit();
// }
?>