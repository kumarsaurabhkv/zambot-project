<?php
include 'database/DBController.php';
include 'header.php';

// Function to add product to cart and check if it's successful
function addToCart($conn, $userId, $productDetails) {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_name = ?");
    $stmt->bind_param("is", $userId, $productDetails['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Product already exists in the cart
        return false;
    } else {
        // Insert the product into the cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_name, price, image, quantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsi", $userId, $productDetails['name'], $productDetails['price'], $productDetails['image'], $productDetails['quantity']);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

// Fetch product details based on product ID from the query string
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}


$userId = 1; 

// Initialize the message variable
$message = '';

// Function to display messages
function displayMessage($message) {
    if (!empty($message)) {
        echo '<div class="text-green-500">' . $message . '</div>';
    }
}

if (isset($_POST['add_to_cart'])) {
    $productDetails = [
        'name' => $_POST['product_name'],
        'price' => $_POST['price'],
        'image' => $_POST['image'],
        'quantity' => 1 
    ];
    if (addToCart($conn, $userId, $productDetails)) {
        // Product added successfully
        $message = "Product added to cart successfully.";
    } else {
        // Failed to add product
        $message = "Failed to add product to cart. Product may already exist in cart.";
    }
}
?>

<section class="text-gray-600 body-font overflow-hidden">
    <div class="container px-5 py-24 mx-auto">

        <div class="lg:w-4/5 mx-auto flex flex-wrap">
            <img alt="Product" class="lg:w-1/2 w-full lg:h-auto h-64 object-cover object-center rounded" src="images/<?php echo $product['image']; ?>" />
            <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                <h2 class="text-sm title-font text-gray-500 tracking-widest">BRAND NAME</h2>
                <h1 class="text-gray-900 text-3xl title-font font-medium mb-1"><?php echo $product['name']; ?></h1>
                <div class="flex mb-4">
                    <!-- Rating Stars -->
                </div>
                <p class="leading-relaxed"><?php echo $product['description']; ?></p>
                <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-100 mb-5">
                    <!-- Color and Size options -->
                </div>
                <div class="flex">
                    <span class="title-font font-medium text-2xl text-gray-900">$<?php echo $product['price']; ?></span>
                    <form method="post" action="">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="image" value="<?php echo $product['image']; ?>">
                        <button type="submit" name="add_to_cart" class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">Add to Cart</button>
                    </form>
                    <!-- Wishlist Button -->
                    <button class="rounded-full w-10 h-10 bg-gray-200 p-0 border-0 inline-flex items-center justify-center text-gray-500 ml-4">
                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                            <!-- Heart Icon -->
                        </svg>
                    </button>
                </div>
                <?php displayMessage($message); ?>
            </div>
        </div>
    </div>
</section>
<?php
include 'footer.php';
?>