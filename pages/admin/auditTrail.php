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
                        <th style="font-size: 30px; width: 10%;">#</th>
                        <th style="font-size: 30px; width: 20%;">User_ID</th>
                        <th style="font-size: 30px; width: 25%;">Date</th>
                        <th style="font-size: 30px; width: 45%;">Action</th>
                    </thead>

                    <tbody>
                        <?php
                        $query = "SELECT * FROM audittable";
                        $edit_query = mysqli_query($conn, $query);
                        if (mysqli_num_rows($edit_query) > 0) {
                            $count=0;
                            while ($row = mysqli_fetch_assoc($edit_query)) {
                                $count++;
                        ?>
                                <tr >
                                    <td style="font-size: 25px;"><?=$count ?></td>
                                    <td style="font-size: 25px;"><?= $row['uid']?></td>
                                    <td style="font-size: 25px;"><?= $row['dateTime']?></td>
                                    <td style="font-size: 25px; padding-left:12px;" align="left"><?= $row['description']?></td>
                                    

                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</body>
</html>