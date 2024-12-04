<?php
session_start();
include 'database/DBController.php';

$message = ''; // Initialize message variable

if(isset($_POST['submit'])) {
   // Escape user inputs to prevent SQL injection
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = mysqli_real_escape_string($conn, $_POST['password']);

   // Perform query to check user credentials
   $select = mysqli_query($conn, "SELECT * FROM `user_info` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select) > 0) {
      $row = mysqli_fetch_assoc($select);
      // Verify password
      if(password_verify($password, $row['password'])) {
         $_SESSION['user_id'] = $row['id'];
         header('location:index.php');
         exit();
      } else {
         $message = 'Incorrect password!';
      }
   } else {
      $message = 'User not found!';
   }
}
?>

<?php
ob_start();
include 'header.php';
?>

<div class="bg-white py-6 sm:py-8 lg:py-12">
  <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
    <h2 class="mb-4 text-center text-2xl font-bold text-gray-800 md:mb-8 lg:text-3xl">Login</h2>

    <form action="" method="post" class="mx-auto max-w-lg rounded-lg border"> 
      <div class="flex flex-col gap-4 p-4 md:p-8">
        <div>
          <label for="email" class="mb-2 inline-block text-sm text-gray-800 sm:text-base">Email</label>
          <input name="email" type="email" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" />
        </div>

        <div>
          <label for="password" class="mb-2 inline-block text-sm text-gray-800 sm:text-base">Password</label>
          <input name="password" type="password" class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none ring-indigo-300 transition duration-100 focus:ring" />
        </div>

        <button type="submit" name="submit" class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-gray-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Log in</button>
      </div>
    </form> 

    <div class="flex items-center justify-center bg-gray-100 p-4">
      <p class="text-center text-sm text-gray-500">Don't have an account? <a href="signup.php" class="text-indigo-500 transition duration-100 hover:text-indigo-600 active:text-indigo-700">Register</a></p>
    </div>

    <?php if(!empty($message)): ?>
      <div class="text-red-500"><?php echo $message; ?></div>
    <?php endif; ?>
  </div>
</div>

<?php
include 'footer.php';
?>
