<?php
include 'header.php';
include 'database/DBController.php';

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

// Function to remove a product from the cart
function removeFromCart($conn, $cartItemId) {
    $query = "DELETE FROM cart WHERE id = $cartItemId";
    return mysqli_query($conn, $query);
}


$userId = 1; 
$cart_items = getCartItems($conn, $userId);
?>
    <div class="container mx-auto py-8">
        <h2 class="text-3xl font-semibold mb-4">Shopping Cart</h2>
        <?php if(empty($cart_items)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Product Name</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Quantity</th>
                            <th class="px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_items as $item): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo $item['product_name']; ?></td>
                                <td class="border px-4 py-2"><?php echo $item['price']; ?></td>
                                <td class="border px-4 py-2"><?php echo $item['quantity']; ?></td>
                                <td class="border px-4 py-2">
                                    <form method="post" action="">
                                        <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="remove_from_cart" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <form method="post" action="checkout.php">
                    <button type="submit" name="checkout" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Proceed to Checkout</button>
                </form>
            </div>
        <?php endif; ?>

        <?php
        // Remove item from cart if remove button is clicked
        if(isset($_POST['remove_from_cart'])) {
            $cartItemId = $_POST['cart_item_id'];
            if(removeFromCart($conn, $cartItemId)) {
                header("Location: cart.php");
                exit();
            } else {
                echo "Failed to remove item from cart.";
            }
        }
        ?>
    </div>
