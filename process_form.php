<?php
// Include DBController.php to access the database connection
include 'database/DBController.php';

// Debugging inclusion
if (file_exists('database/DBController.php')) {
    echo "DBController.php exists";
} else {
    echo "DBController.php does not exist";
}


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize DBController
    $db = new DBController();

    // Retrieve form data
    $firstName = htmlspecialchars($_POST['first-name']);
    $lastName = htmlspecialchars($_POST['last-name']);
    $company = htmlspecialchars($_POST['company']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Insert data into contact_form table
    $query = "INSERT INTO contact_form (first_name, last_name, company, email, subject, message) VALUES (?, ?, ?, ?, ?, ?)";
    $params = array($firstName, $lastName, $company, $email, $subject, $message);
    $stmt = $db->connect()->prepare($query);
    
    if ($stmt->execute($params)) {
        // Data inserted successfully
        echo "Data inserted successfully.";
    } else {
        // Error occurred
        echo "Error inserting data.";
    }
} else {
    // Redirect to homepage or display an error message
    echo "Form submission error.";
}
?>
