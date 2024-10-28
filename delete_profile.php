<?php
session_start();
require 'connection.php'; 

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['user_email'];

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete associated orders first
        $stmt = $conn->prepare('DELETE FROM orders WHERE user_email = ?');
        $stmt->bind_param('s', $email); 
        $stmt->execute(); // Execute the statement

        // Delete user from the database
        $stmt = $conn->prepare('DELETE FROM user WHERE email = ?');
        $stmt->bind_param('s', $email); 
        $stmt->execute(); 
        
        // Commit the transaction
        $conn->commit();

        // Destroy session and redirect to the homepage
        session_destroy();
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if anything goes wrong
        $conn->rollback();
        echo "Failed to delete profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Profile</title>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete your account? This action cannot be undone.");
        }
    </script>
</head>
<body>
    <h1>Delete Profile</h1>
    <form method="POST" onsubmit="return confirmDelete();">
        <p>Are you sure you want to delete your account?</p>
        <button type="submit" name="delete">Delete Account</button>
        <a href="profile.php">Cancel</a>
    </form>
</body>
</html>
