<?php
session_start();
require '../connection/db_conn.php';

if (isset($_SESSION['user_id'])) {
    $user_name = $_SESSION['user_name'];
    $id = $_SESSION['user_id'];
} else {
    session_unset();
    session_destroy();
    header("Location: ../pages/index.php");
}

if (isset($_POST['submit'])) {
    $query_cart = "SELECT * FROM cart WHERE userID = '$id'";
    $check_cart = mysqli_query($conn, $query_cart);
    $queryniLester = "SELECT MAX(groupID) as maxInSales FROM summaryofsales";
    $resultLester = mysqli_query($conn, $queryniLester);
    $maxvaluesaSales = mysqli_fetch_assoc($resultLester);
    $newGroupID =  $maxvaluesaSales['maxInSales'] + 1;

    $resultReceipt = mysqli_query($conn, "SELECT MAX(numberOfReceipt) as maxInReceipt FROM summaryofsales");
    $newMaxNumberOfReceipt = mysqli_fetch_assoc($resultReceipt);
    $newReceiptNo = $newMaxNumberOfReceipt['maxInReceipt'] + 1;

    $productID = 0;
    $summers = 0;
    while ($row = mysqli_fetch_assoc($check_cart)) {
        $summers += $row['sumPurchase'];
    }

    echo "ilan ang total??", $summers;

    // Initialize cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/links",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'data' => [
                'attributes' => [
                    'amount' => $summers * 100,
                    'description' => $user_name . ' purchased a product',
                    'payment_method_types' => ['gcash', 'credit_card'], // Adjust based on your available payment methods
                ],
            ],
        ]),
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Basic c2tfdGVzdF9RSFhSVnVwVldFTEJ3VWNGbURjYmJWRHA6", // Use your actual secret key
            "content-type: application/json"
        ],
    ]);

    // Execute the cURL request
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        // Handle cURL error
        echo "cURL Error #:" . $err;
    } else {
        // Decode the response
        $responseBody = json_decode($response, true);
        // Check if the response is valid and contains the payment link
        if (isset($responseBody['data']['attributes']['checkout_url'])) {
            //dine ilalagay
            $apiIdLink = $responseBody['data']['id'];
            $check_cart2 = mysqli_query($conn, "SELECT * FROM cart WHERE userID = '$id'");
            if (mysqli_num_rows($check_cart2) > 0) {
                while ($row = mysqli_fetch_assoc($check_cart2)) {
                    echo "pumasok kaba dine? ilang beses??(2)";
                    $productID = $row['productID'];
                    $quantityPurchase = $row['quantityPurchase'];
                    $sumPurchase = $row['sumPurchase']; //this is the total of the item

                    $queryniLester2 = "INSERT INTO summaryofsales 
                            SELECT '', '$newGroupID', '$id', '$productID', '$quantityPurchase', '$sumPurchase', NOW(), 'none', '$apiIdLink', 'checking', '$newReceiptNo'";
                    $resultLester2 = mysqli_query($conn, $queryniLester2);
                    if (!$resultLester2) {
                        die("Error inserting data into summaryofsales: " . mysqli_error($conn));
                    }

                    $delete_query = "DELETE FROM cart WHERE userID = '$id'";
                    $delete_cart = mysqli_query($conn, $delete_query);
                    if (!$delete_cart) {
                        die("Error deleting from cart: " . mysqli_error($conn));
                    }

                    $updateStock = mysqli_query($conn, "UPDATE items SET vStock = vStock - '$quantityPurchase' WHERE itemID = '$productID'");
                    $updateOrdered = mysqli_query($conn, "UPDATE ordered SET quantityPurchase = quantityPurchase + '$quantityPurchase' WHERE productID = '$productID'");
                    if (!$updateStock || !$updateOrdered) {
                        die("Error updating stock or ordered: " . mysqli_error($conn));
                    }
                }
            } else {
                echo "alang laman ate ko";
            }

            // Redirect to the payment link , before ma punta sa payment link, lagay muna sa database yung mga data
            $paymentLink = $responseBody['data']['attributes']['checkout_url'];
            header("Location: $paymentLink"); 
            // print_r($responseBody); // Display the full response

        } else {
            // Print the entire response body to understand the error
            echo "Error: Payment link not found in response.";
        }
    }
}

if (isset($_POST['submitVer'])) {
    $receiptNumber = mysqli_real_escape_string($conn, $_POST['hiddenvalue']);

    if ($_POST['submitVer'] == "button1") {
        $result = mysqli_query($conn, "SELECT * FROM summaryofsales WHERE numberOfReceipt = '$receiptNumber'");
        $summaryofsales = mysqli_fetch_assoc($result);
        //ere tatawagin with bracket para makuha yung cuslinkID

        $url = 'https://api.paymongo.com/v1/links/' . $summaryofsales['cusLinkID'];


        // Initialize cURL
        $curl = curl_init($url);

        // Set the headers, including the authorization
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'authorization: Basic ' . base64_encode('sk_test_QHXRVupVWELBwUcFmDcbbVDp')
        ]);

        // Set cURL options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return response as string
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // Specify GET method

        // Execute the request
        $response = curl_exec($curl);

        // Close cURL
        curl_close($curl);

        // Decode and handle the response
        if ($response) {
            $data = json_decode($response, true);
            // print_r($data); // Print or process the data as needed
        
        //pwede ng mag update ng database
        $ModeOfPayment = $data['data']['attributes']['payments'][0]['data']['attributes']['source']['type'];
        $PaymentStatus = $data['data']['attributes']['status'];

        $resultButton1 = mysqli_query($conn, "UPDATE summaryofsales SET cusModePayment = '$ModeOfPayment', cusPaymentStatus = '$PaymentStatus' WHERE numberOfReceipt = '$receiptNumber'");

        if($resultButton1){
            header("Location: ../pages/admin/verifyOrder.php");
            exit();
        }else{
            echo "nagka error";
        }
        } else {
            echo "dine pumapasok sa error";
            echo 'Error: ' . curl_error($curl);
        }
    } else if ($_POST['submitVer'] == "button2") {
        $result = mysqli_query($conn, "SELECT * FROM summaryofsales WHERE numberOfReceipt = '$receiptNumber'");
        $summaryofsales = mysqli_fetch_assoc($result);
        //ere tatawagin with bracket para makuha yung cuslinkID

        $url = 'https://api.paymongo.com/v1/links/' . $summaryofsales['cusLinkID'];


        // Initialize cURL
        $curl = curl_init($url);

        // Set the headers, including the authorization
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'authorization: Basic ' . base64_encode('sk_test_QHXRVupVWELBwUcFmDcbbVDp')
        ]);

        // Set cURL options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return response as string
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // Specify GET method

        // Execute the request
        $response = curl_exec($curl);

        // Close cURL
        curl_close($curl);

        // Decode and handle the response
        if ($response) {
            $data = json_decode($response, true);
        
        //pwede ng mag update ng database
        $ModeOfPayment = $data['data']['attributes']['payments'][0]['data']['attributes']['source']['type'];
        $PaymentStatus = $data['data']['attributes']['status'];

        $resultButton1 = mysqli_query($conn, "UPDATE summaryofsales SET cusModePayment = '$ModeOfPayment', cusPaymentStatus = '$PaymentStatus' WHERE numberOfReceipt = '$receiptNumber'");

        if($resultButton1){
            header("Location: ../pages/admin/verifyOrder.php");
            exit();
        }else{
            echo "nagka error";
        }
        } else {
            echo "dine pumapasok sa error";
            echo 'Error: ' . curl_error($curl);
        }
    } else if ($_POST['submitVer'] == "button3") {
    }
}
