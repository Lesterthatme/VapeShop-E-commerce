<?php
require '../../connection/db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary of sales</title>

    <link rel="stylesheet" href="../../assets/css/sos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="assets/js/Digis.js">
    <link rel="stylesheet" href="assets/js/chartJS.js">


</head>

<body>
    <?php include 'sidebar.php' ?>

    <main class="main-container">


        <div class="main-cards">
            <div class="card">

            </div>

            <div class="card">
                <div class="card-inner">
                    <p class="text-primary">NUMBER OF ORDERS</p>
                    <span class="material-symbols-outlined text-orange">add_shopping_cart</span>
                </div>
                <?php
                $result02 = mysqli_query($conn, "SELECT SUM(quantityPurchase) as summers FROM ordered");
                $row = mysqli_fetch_assoc($result02);
                ?>
                <span class="text-primary font-weight-bold"><?= $row['summers'] ?></span>

            </div>

            <div class="card">
                <div class="card-inner">
                    <p class="text-primary">TOTAL SALES</p>
                    <span class="material-symbols-outlined text-green">shopping_cart</span>
                </div>

                <?php
                $result03 = mysqli_query($conn, "SELECT SUM(sumPurchase) as sales FROM summaryofsales");
                $rows = mysqli_fetch_assoc($result03);
                ?>
                <span class="text-primary font-weight-bold">PHP <?= $rows['sales'] ?></span>

            </div>

            <div class="card">
                <div class="card-inner">
                    <p class="text-primary">EXPIRED PRODUCTS</p>
                    <span class="material-symbols-outlined text-red">notification_important</span>
                </div>
                <?php
                $result04 = mysqli_query($conn, "SELECT SUM(vStock) as expired FROM items WHERE NOW() > vExpDate");
                $rows = mysqli_fetch_assoc($result04);
                ?>
                <span class="text-primary font-weight-bold"><?= $rows['expired'] ?></span>

            </div>
        </div>

        <div class="bodyofROS">

            <div class="outerdiv">
                <form action="" method="POST">
                    <div class="dateprompt">
                        <h1>Request Date</h1>
                        <div class="dates">
                            <div class="date1">
                                <label for="araw1">From Date:</label>
                                <input type="date" name="fromDate" id="araw1">
                            </div>
                            <div class="date2">
                                <label for="araw2">To Date:</label>
                                <input type="date" name="toDate" id="araw2">
                            </div>
                        </div>
                        <div class="btn">
                            <button type="submit" formaction="sos.php"> RESET</button>
                            <button type="submit" name="submit-btn-sos">SUBMIT</button>
                        </div>
                    </div>
                </form>

                <div class="boxSos">
                    <table style="width: 98%;">
                        <tr>
                            <th style="width: 30%; font-size: 25px; font-weight:900;">DATE</th>
                            <th style="border-left: 2px solid rgb(108, 105, 105); font-size: 25px; font-weight:900;">TOTAL OF ORDERS</th>
                            <th style="width: 30%; border-left: 2px solid rgb(108, 105, 105); font-size: 25px; font-weight:900;">SUM</th>
                        </tr>
                        <?php

                        if (isset($_POST['submit-btn-sos'])) {
                            $fromDate = $_POST['fromDate'];
                            $toDate = $_POST['toDate'];

                            $query = "SELECT Date ,SUM(quantityPurchase) AS qty, SUM(sumPurchase) as total 
                            FROM summaryofsales WHERE Date BETWEEN '$fromDate' AND '$toDate' 
                            GROUP BY Date";
                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0) {
                                foreach ($result as $temp) {
                        ?>
                                    <tr>
                                        <td align="CENTER"><?= $temp['Date'] ?></td>
                                        <td align="center"><?= $temp['qty'] ?></td>
                                        <td align="center"><?= $temp['total'] ?></td>
                                    </tr>
                            <?php
                                }
                            }

                            $query1 = "SELECT SUM(sumPurchase) as totalnakinita FROM summaryofsales WHERE Date BETWEEN '$fromDate' AND '$toDate'";
                            $result1 = mysqli_query($conn, $query1);
                            $temp = mysqli_fetch_assoc($result1);
                            $totalsummation = $temp['totalnakinita'];
                            ?>
                            <tr>
                                <td colspan="2" align="right" style="padding-right: 15px; border-top: 2px solid black; font-weight:700;">Total:</td>
                                <td align="center" style="padding-right: 15px; border-top: 2px solid black;">Php <?= $totalsummation ?></td>
                            </tr>
                        <?php
                        }
                        ?>

                    </table>
                </div>
            </div>
        </div>

        <div class="summaryofsales">
        <br> <br>
        <div class="headersasummaryofsales">
            <h1 style="font-size: 3rem;">FULL SUMMARY OF SALES</h1>
        </div>

        <div class="showsection">
            <table style="border: 2px solid white;">
                <tr>
                    <th style="width: 10%; border: 2px solid white">GROUP ID</th>

                    <th style="border: 2px solid white">NAME</th>

                    <th style="border: 2px solid white">PRODUCT</th>

                    <th style="border: 2px solid white">QUANTITY</th>

                    <th style="border: 2px solid white">PURCHASE PRICE</th>

                    <th style="width: 12%; border: 2px solid white">DATE</th>

                    <th style="width: 12%; border: 2px solid white">METHOD</th>
                    
                </tr>
                <?php
                $query = "SELECT summaryofsales.groupID, users.fName, users.lName ,items.vFlavor, summaryofsales.quantityPurchase, summaryofsales.sumPurchase, summaryofsales.Date, summaryofsales.cusModePayment from summaryofsales JOIN users ON summaryofsales.userID = users.id JOIN items ON items.itemID = summaryofsales.productID";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    foreach ($result as $temp) {
                        $tempName = $temp['fName'] . ' ' . $temp['lName'];
                ?>
                        <tr>
                            <td style="border: 2px solid white" align="center"><?= $temp['groupID'] ?></td>
                            <td style="border: 2px solid white" align="center"><?= $tempName ?></td>

                            <td style="border: 2px solid white" align="center"><?= $temp['vFlavor']  ?></td>

                            <td style="border: 2px solid white" align="center"><?= $temp['quantityPurchase'] ?></td>
                            <td style="border: 2px solid white" align="center"><?= $temp['sumPurchase']  ?></td>
                            <td style="border: 2px solid white" align="center"><?= $temp['Date']  ?></td>
                            <td style="border: 2px solid white" align="center"><?= $temp['cusModePayment']  ?></td>
                            
                            
            <?php
                            
                        }
                    }
            ?>

            </table>
        </div>
    </div>
    </main>
</body>

</html>