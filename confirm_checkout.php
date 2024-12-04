<?php
include 'database/DBController.php';
include 'header.php';

// Function to fetch cart items for a specific user
function getCartItems($conn, $userId) {
    $cart_items = array();
    $query = "SELECT * FROM cart WHERE user_id = $userId";
    $result = mysqli_query($conn, $query);
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $cart_items[] = $row;
        }
    }
    return $cart_items;
}

// Function to empty the cart after successful checkout
function emptyCart($conn, $userId) {
    $query = "DELETE FROM cart WHERE user_id = $userId";
    return mysqli_query($conn, $query);
}


$userId = 1;
$payment_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST["payment_method"];
    if ($payment_method === "upi" || $payment_method === "cash") {
        // Payment successful
        $payment_success = true;
        // Empty the cart
        emptyCart($conn, $userId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            <?php if ($payment_success): ?>
                <h2 class="text-2xl font-semibold mb-4">Payment Successful</h2>
                <h3 class="text-xl font-semibold mb-2">Your Order Details:</h3>
                <ul>
                    <?php
                    $cart_items = getCartItems($conn, $userId);
                    foreach($cart_items as $item):
                    ?>
                    <li><?php echo $item['product_name']; ?> - $<?php echo $item['price']; ?> (Quantity: <?php echo $item['quantity']; ?>)</li>
                    <?php endforeach; ?>
                </ul>
                <p>Thank you for your purchase!</p>
            <?php else: ?>
                <h2 class="text-2xl font-semibold mb-4">Payment Failed</h2>
                <p>Oops! There was an error processing your payment. Please try again later.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
