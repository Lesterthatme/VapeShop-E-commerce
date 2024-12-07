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

    <div class="maininventory">
        <div class="container" style="padding-right:70px;">

            <section class="display-products" id="display">
                <table>
                    <thead>
                        <th style="font-size: 30px;">Order#</th>
                        <th style="font-size: 30px;">Status</th>
                        <th style="font-size: 30px;">Action</th>

                    </thead>

                    <tbody>
                        <?php
                        $query = "SELECT * FROM summaryofsales";
                        $edit_query = mysqli_query($conn, $query);
                        if (mysqli_num_rows($edit_query) > 0) {
                            $isangBeseslang = 0;
                            $temp = 0;
                            while ($row = mysqli_fetch_assoc($edit_query)) {
                                $isangBeseslang = $row['numberOfReceipt'];

                                if ($isangBeseslang == $temp) {
                                    continue;
                                }
                        ?>

                                <tr>
                                    <td style="width: 30%"><?= $row['numberOfReceipt']; ?></td>



                                    <?php
                                    if ($row['cusPaymentStatus'] == "checking") {
                                        echo "<td style='background-color:gray; color: black;width: 30%;'>";
                                        echo "<b> Checking </b>";
                                        echo "</td>";
                                    } else if ($row['cusPaymentStatus'] == "paid") {
                                        echo "<td style='background-color:lightgreen; width: 30%;'>";
                                        echo "<b> Paid </b>";
                                        echo "</td>";
                                    } else if ($row['cusPaymentStatus'] == "unpaid") {
                                        echo "<td style='background-color:red; color: white; width: 30%;'>";
                                        echo "<b> Unpaid </b>";
                                        echo "</td>";
                                    }
                                    ?>
                                    <!-- naka form re -->
                                    <form action="../../function/checkoutFunc.php" method="POST">
                                        <td style="width: 40%">
                                            <div class="bodyNgChecking">
                                                <input type="text" hidden name="hiddenvalue" value="<?= $row['numberOfReceipt'] ?>">
                                                <?php
                                                if ($row['cusPaymentStatus'] == "checking" && $isangBeseslang != $temp) {
                                                    $temp = $isangBeseslang;
                                                ?>
                                                    <button type="submit" name="submitVer" value="button1" class="btnSaChecking">Check</button>
                                                <?php
                                                } else if ($row['cusPaymentStatus'] != "checking" && $isangBeseslang != $temp) {
                                                    $temp = $isangBeseslang;
                                                ?>
                                                    <button type="submit" name="submitVer" value="button2" class="btnSaChecking">Check Again</button>
                                                    <button type="submit" name="submitVer" value="button3" class="btnSaChecking">Delete</button>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </form>
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