<?php
require '../../connection/db_conn.php';

$currentDateTime = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail</title>

    <link rel="stylesheet" href="../../assets/css/inventory.css">
</head>
<body>
    <?php include 'sidebar.php' ?>

    <div class="maininventory" >
        <div class="container" style="padding-right:70px;">

            <section class="display-products" id="display">
                <table>
                    <thead >
                        <th style="font-size: 30px;">image</th>
                        <th style="font-size: 30px;">name</th>
                        <th style="font-size: 30px;">price</th>
                        <th style="font-size: 30px;">stocks</th>
                        <th style="font-size: 30px;">Date Added</th>
                        <th style="font-size: 30px;">expiration</th>
                        <th style="font-size: 30px;">status</th>
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
                                    
                                    <?php
                                        if($row['vStock'] == 0){
                                            echo "<td style='background-color:red; color: black;'>";
                                            echo "<b> Out of stock </b>";
                                            echo "</td>";
                                        }else if($row['vStock'] >= 0 && $row['vExpDate'] >= $currentDateTime){
                                            echo "<td style='background-color:lightgreen;'>";
                                            echo "<b> Available </b>";
                                            echo "</td>";
                                        } 
                                        else if($currentDateTime >= $row['vExpDate'] ){
                                            echo "<td style='background-color:red; color: white;'>";
                                            echo "<b> Expired </b>";
                                            echo "</td>";
                                        }
                                    ?>
                                    

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