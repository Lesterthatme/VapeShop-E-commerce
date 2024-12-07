<?php
require '../../connection/db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Seller</title>
    <link rel="stylesheet" href="../../assets/css/inventory.css">

</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="maininventory">
        <div class="container" style="padding-right:70px;">

            <section class="display-products" id="display">
                <table>
                    <thead>
                        <th style="font-size: 30px;">image</th>
                        <th style="font-size: 30px;">name</th>
                        <th style="font-size: 30px;">quantity</th>
                    </thead>

                    <tbody>
                        <?php
                        $query = "SELECT items.image, items.vFlavor, SUM(ordered.quantityPurchase) AS total_quantity FROM items JOIN ordered ON items.itemID = ordered.productID GROUP BY items.image, items.vFlavor, ordered.productID ORDER BY total_quantity DESC";

                        $edit_query = mysqli_query($conn, $query);

                        if (mysqli_num_rows($edit_query) > 0) {
                            while ($row = mysqli_fetch_assoc($edit_query)) {
                        ?>

                                <tr>
                                    <td><img src="imagesSaInventory/<?= $row['image']; ?>" height="100" alt=""></td>

                                    <td><?= $row['vFlavor']; ?></td>

                                    <td><?= $row['total_quantity']; ?></td>


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