<!-- <?php
require '../connection/db_conn.php'; // Your database connection

// Read the webhook payload from PayMongo
$payload = file_get_contents("php://input");
$event = json_decode($payload, true);

// Verify if the webhook event is of type 'payment.paid'
if ($event['data']['type'] === 'payment.paid') {
    $data = $event['data']['attributes'];

    // Retrieve order details from metadata or custom fields
    $userId = $data['metadata']['user_id'];  // Assuming you store user_id in metadata
    $productId = $data['metadata']['product_id'];
    $quantityPurchased = $data['metadata']['quantity'];

    // Update your database to reflect the successful purchase
    $updateStockQuery = "UPDATE items SET vStock = vStock - '$quantityPurchased' WHERE itemID = '$productId'";
    mysqli_query($conn, $updateStockQuery);

    $insertOrderQuery = "INSERT INTO summaryofsales (userID, productID, quantity, amount, date)
                         VALUES ('$userId', '$productId', '$quantityPurchased', '{$data['amount']}', NOW())";
    mysqli_query($conn, $insertOrderQuery);

    // Respond to PayMongo to acknowledge receipt of the webhook
    http_response_code(200);
    echo json_encode(["message" => "Webhook received and processed successfully"]);
} else {
    // Invalid or unhandled event type
    http_response_code(400);
    echo json_encode(["message" => "Event not handled"]);
}
?> -->
