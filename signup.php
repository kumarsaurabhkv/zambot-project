<?php
session_start();
include 'database/DBController.php';

$message = ''; // Initialize message variable

if(isset($_POST['submit'])) {
   // Check if all form fields are set
   if(isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['cpassword'])) {
      // Escape user inputs to prevent SQL injection
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $password = mysqli_real_escape_string($conn, $_POST['password']);
      $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

      // Check if passwords match
      if($password !== $cpassword) {
         $message = 'Passwords do not match!';
      } else {
         // Hash the password
         $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

         // Perform query to insert user data into the database
         $insert = mysqli_query($conn, "INSERT INTO `user_info` (name, email, password) VALUES ('$name', '$email', '$hashedPassword')") or die('query failed');

         if($insert) {
            $message = 'User registered successfully!';
         } else {
            $message = 'Error registering user!';
         }
      }
   } else {
      $message = 'All fields are required!';
   }
}
?>

<?php
ob_start();
include 'header.php';
?>

<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
    <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-8 lg:text-3xl">Sign Up</h2>

    <form action="" method="post" class="mx-auto max-w-lg rounded-lg border">
      <div class="flex flex-col gap-4 p-4 md:p-8">
        <div>
          <label for="name" class="mb-2 inline-block text-sm text-gray-800 sm:text-base">Name</label>
          <input name="name" type="text" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" required />
        </div>

        <div>
          <label for="email" class="mb-2 inline-block text-sm text-gray-800 sm:text-base">Email</label>
          <input name="email" type="email" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" required />
        </div>

        <div>
          <label for="password" class="mb-2 inline-block text-sm text-gray-800 sm:text-base">Password</label>
          <input name="password" type="password" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" required />
        </div>

        <div>
          <label for="cpassword" class="mb-2 inline-block text-sm text-gray-800 sm:text-base">Confirm Password</label>
          <input name="cpassword" type="password" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" required />
        </div>

        <button type="submit" name="submit" class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-gray-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Sign Up</button>
      </div>
    </form>

    <div class="flex items-center justify-center bg-gray-100 p-4">
      <p class="text-center text-sm text-gray-500">Already have an account? <a href="login.php" class="text-indigo-500 transition duration-100 hover:text-indigo-600 active:text-indigo-700">Login</a></p>
    </div>

    <?php if(!empty($message)): ?>
      <div class="text-red-500"><?php echo $message; ?></div>
    <?php endif; ?>
  </div>
</div>
<?php
include 'footer.php';
?>
