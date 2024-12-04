<?php
include 'database/DBController.php';
include 'header.php';

// Function to add product to cart and check if it's successful
function addToCart($conn, $userId, $productName, $price, $image, $quantity) {
    // Check if the product is already in the cart
    $check_query = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $userId AND product_name = '$productName'");
    if(mysqli_num_rows($check_query) > 0) {
        // Product already exists in the cart
        return false;
    } else {
        // Insert the product into the cart
        $insert_query = "INSERT INTO cart (user_id, product_name, price, image, quantity) VALUES ($userId, '$productName', $price, '$image', $quantity)";
        if(mysqli_query($conn, $insert_query)) {
            return true;
        } else {
            return false;
        }
    }
}

$select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');


$userId = 1; // 

// Check if form is submitted to add product to cart
if(isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $quantity = 1; // 
    if(addToCart($conn, $userId, $productName, $price, $image, $quantity)) {
        // Product added successfully
        $message = "Product added to cart successfully.";
    } else {
        // Failed to add product
        $message = "Failed to add product to cart. Product may already exist in cart.";
    }
}
?>

<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
    <div class="mb-6 flex items-end justify-between gap-4">
      <h2 class="text-2xl font-bold text-gray-800 lg:text-3xl">Shop</h2>

      <a href="index.php" class="inline-block rounded-lg border bg-white px-4 py-2 text-center text-sm font-semibold text-gray-500 outline-none ring-indigo-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:px-8 md:py-3 md:text-base">Go Home</a>
    </div>

    <div class="grid gap-x-4 gap-y-8 sm:grid-cols-2 md:gap-x-6 lg:grid-cols-3 xl:grid-cols-4">
    <?php
    if(mysqli_num_rows($select_product) > 0){
        while($fetch_product = mysqli_fetch_assoc($select_product)){
    ?>
      <div>
        <a href="single_product.php?id=<?php echo $fetch_product['id']; ?>" class="group relative mb-2 block h-80 overflow-hidden rounded-lg bg-gray-100 lg:mb-3">
          <img src="images/<?php echo $fetch_product['image']; ?>?auto=format&fit=crop&w=600" loading="lazy" alt="<?php echo $fetch_product['name']; ?>" class="h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />

          <?php if($fetch_product['on_sale']): ?>
          <span class="absolute left-0 top-0 rounded-br-lg bg-red-500 px-3 py-1.5 text-sm uppercase tracking-wider text-white">sale</span>
          <?php endif; ?>
        </a>

        <div>
          <a href="sinle_product.php?id=<?php echo $fetch_product['id']; ?>" class="hover:gray-800 mb-1 text-gray-500 transition duration-100 lg:text-lg"><?php echo $fetch_product['name']; ?></a>

          <div class="flex items-end gap-2">
            <span class="font-bold text-gray-800 lg:text-lg">Rs-<?php echo $fetch_product['price']; ?></span>
            <?php if($fetch_product['on_sale']): ?>
            <span class="mb-0.5 text-red-500 line-through">$<?php echo $fetch_product['regular_price']; ?></span>
            <?php endif; ?>
          </div>

          <form method="post" action="">
            <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
            <input type="hidden" name="price" value="<?php echo $fetch_product['price']; ?>">
            <input type="hidden" name="image" value="<?php echo $fetch_product['image']; ?>">
            <button type="submit" name="add_to_cart" class="mt-4 w-full bg-indigo-500 text-white py-2 px-4 rounded-md transition duration-300 hover:bg-indigo-600">Add to Cart</button>
          </form>
        </div>
      </div>
    <?php
        }
    } else {
        echo "<p>No products available.</p>";
    }
    ?>
    </div>
    
    <?php if(isset($message)): ?>
    <div class="mt-4 text-gray-800"><?php echo $message; ?></div>
    <?php endif; ?>
    
  </div>
</div>
