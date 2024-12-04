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

// Function to calculate total amount in cart
function calculateTotalAmount($cart_items) {
    $total_amount = 0;
    foreach ($cart_items as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    return $total_amount;
}


$userId = 1; 
$cart_items = getCartItems($conn, $userId);
$total_amount = calculateTotalAmount($cart_items);
?>

<div class="container mx-auto py-8">
    <h2 class="text-3xl font-semibold mb-4">Checkout</h2>
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4">Your Cart</h3>
        <?php if(empty($cart_items)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <ul>
                <?php foreach($cart_items as $item): ?>
                    <li><?php echo $item['product_name']; ?> - $<?php echo $item['price']; ?> (Quantity: <?php echo $item['quantity']; ?>)</li>
                <?php endforeach; ?>
            </ul>
            <p class="font-semibold mt-4">Total Amount: $<?php echo $total_amount; ?></p>
        <?php endif; ?>
        <div role="alert" class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>Your purchase has been confirmed! Total Amount: $<?php echo $total_amount; ?></span>
        </div>
        <h3 class="text-xl font-semibold mb-4">Payment Options</h3>
        <form action="confirm_checkout.php" method="POST">
            <div class="mb-4">
                <input type="radio" id="upi" name="payment_method" value="upi" class="mr-2">
                <label for="upi" class="font-semibold">UPI</label>
            </div>
            <div class="mb-4">
                <input type="radio" id="cash" name="payment_method" value="cash" class="mr-2">
                <label for="cash" class="font-semibold">Cash on Delivery</label>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Confirm Payment</button>
        </form>
    </div>
</div>
