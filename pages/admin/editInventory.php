<?php
require '../../connection/db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="../../assets/css/inventory.css">

</head>

<body>
    <?php include 'sidebar.php' ?>

    <div class="maininventory">
        <div class="container">
            <section>
                <?php
                $idToUpdate = $_GET['id'];

                $result = mysqli_query($conn, "SELECT * FROM items WHERE itemID = '$idToUpdate'");
                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $row) {
                ?>
                            

                        <form action="../../function/inventoryfunction.php" method="post" class="add-product" enctype="multipart/form-data">
                            <h3>Edit a product</h3>
                            <img src="imagesSaInventory/<?=$row['image'] ?>" alt="pic" height="200">
                            <input type="file" name="image" accept="image/png, image/jpg, image/jpeg, image/avif, image/webp" class="box">
                            <input type="hidden" name="oldimage" value="<?= $row['image']?>" class="box">
                            <input type="hidden" name="itemID" value="<?= $row['itemID']?>" class="box">
                            <input type="text" name="name" value="<?= $row['vFlavor']?>" placeholder="Enter the product name" class="box" required>
                            <input type="number" name="price" value="<?= $row['vPrice']?>" placeholder="Enter the product price" class="box" required>
                            <input type="number" name="stock" value="<?= $row['vStock']?>" placeholder="Enter the stock of the product" class="box" required>
                            <input type="date" name="expiration" value="<?= $row['vExpDate']?>" class="box" required>
                            <input type="submit" value="Update the product" name="update-btn" class="add-btn" required>
                        </form>
                <?php
                    }
                }
                ?>

            </section>

            <section class="display-products" id="display">
                <table>
                    <thead>
                        <th>image</th>
                        <th>name</th>
                        <th>price</th>
                        <th>stocks</th>
                        <th>Date Added</th>
                        <th>expiration</th>
                        <th>action</th>
                    </thead>

                    <tbody>
                        <?php
                        $query = "SELECT * FROM items";
                        $edit_query = mysqli_query($conn, $query);
                        if (mysqli_num_rows($edit_query) > 0) {
                            while ($row = mysqli_fetch_assoc($edit_query)) {
                        ?>

                                <tr>
                                    <td><img src="imagesSaInventory/<?= $row['image']; ?>" height="100" alt=""></td>
                                    <td hidden><?= $row['itemID']; ?></td>
                                    <td><?= $row['vFlavor']; ?></td>
                                    <td><?= $row['vPrice']; ?></td>
                                    <td><?= $row['vStock']; ?></td>
                                    <td><?= $row['vAdded']; ?></td>
                                    <td><?= $row['vExpDate']; ?></td>

                                    <td>
                                        <a href="" class="edit-btn" >update</a>
                                        <form action="" method="post">
                                            <button type="submit" name="delete-btn" class="delete-btn" value="" onclick="">Delete</button>
                                        </form>
                                    </td>

                                </tr>
                        <?php
                            };
                        } else {
                            echo "<div class='empty'><span>no product added</span></div>";
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>

</body>

</html>