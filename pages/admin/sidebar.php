<?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        
        $id = $_SESSION['user_id'] ;
        $name = $_SESSION['firstName'] ;
        $fullname = $_SESSION['user_name'];
    } else {
        session_unset();
        session_destroy();
        header('location:../index.php');
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <!-- css extension -->
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/dash.css">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/cc76298735.js" crossorigin="anonymous"></script>
</head>


<body>
<div class="sidebar">

    <?php
    $result01 = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result01);
    ?>

    <header><span><img src="imagesSaInventory/<?= $row['image']?>" 
    alt="" 
    height="130px" style="margin-top: 10px; border-radius: 50%;"></span></header>
   
    <header style="position: relative; bottom: 15px; color: white; font-weight: 600;"><?= $name ?></header>

    <ul class="main" style="margin-top: 0;">
        <li>
            <a href="sos.php">
                <i class="fas fa-book"></i>
                <span>Summary of Sales</span>
            </a>
        </li>
        <li>
            <a href="bestSeller.php">
                <i class="fas fa-book"></i>
                <span>Best Seller</span>
            </a>
        </li>
        <li>
            <a href="verifyOrder.php">
                <i class="fas fa-clock"></i>
                <span>Verify Order</span>
            </a>
        </li>
        <li>
            <a href="stocks.php">
                <i class="fas fa-clock"></i>
                <span>Stocks</span>
            </a>
        </li>
        <li>
            <a href="inventory.php">
                <i class="fas fa-edit"></i>
                <span>Inventory</span>
            </a>
        </li>
        <li>
            <a href="auditTrail.php">
                <i class="fa-solid fa-trailer"></i>
                <span>Audit Trail</span>
            </a>
        </li>
        <li>
            <a href="adminpage.php">
                <i class="fas fa-user-circle"></i>
                <span>Admin</span>
            </a>
        </li>
    
        <li>
            <a href="../../function/logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>
</body>
</html>