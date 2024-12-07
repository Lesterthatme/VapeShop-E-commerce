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
                <form action="../../function/inventoryfunction.php" method="post" class="add-product" enctype="multipart/form-data">
                    <h3>add a new product</h3>
                    <input type="file" name="image" accept="image/png, image/jpg, image/jpeg, image/avif, image/webp" class="box" required>
                    <input type="text" name="name" placeholder="Enter the product name" class="box" required>
                    <input type="number" name="price" placeholder="Enter the product price" class="box" required>
                    <input type="number" name="stock" placeholder="Enter the stock of the product" class="box" required>
                    <input type="date" name="expiration"  class="box" required>
                    <input type="submit" value="add the product" name="add-product" class="add-btn" required>
                </form>
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
                                        <a href="editInventory.php?id=<?= $row['itemID']; ?>" class="edit-btn">update</a>
                                        <form action="../../function/inventoryfunction.php" method="post">
                                            <input type="hidden" name="image" value="<?= $row['image']?>">
                                            <input type="hidden" name="name" value="<?= $row['vFlavor']?>">
                                            <button type="submit" name="delete-btn" class="delete-btn" value="<?= $row['itemID']; ?>" 
                                                onclick="return confirm('are you sure you want to delete this?');">Delete</button>
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