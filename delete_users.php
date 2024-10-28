<?php
session_start();
require 'connection.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_email']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

// Check if a user email has been passed for deletion
if (!isset($_GET['email'])) {
    header('Location: manage_users.php?status=error&message=No user specified for deletion');
    exit();
}

$delete_email = $_GET['email'];

// Start a transaction
$conn->begin_transaction();

try {
    // Delete associated reviews
    $stmt = $conn->prepare('DELETE FROM reviews WHERE user_email = ?');
    $stmt->bind_param('s', $delete_email);
    $stmt->execute();

    // Delete associated orders
    $stmt = $conn->prepare('DELETE FROM orders WHERE user_email = ?');
    $stmt->bind_param('s', $delete_email);
    $stmt->execute();

    // Delete the user
    $stmt = $conn->prepare('DELETE FROM user WHERE email = ?');
    $stmt->bind_param('s', $delete_email);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();

    // Redirect to manage_users.php with a success message
    header('Location: manage_users.php?status=success&message=User deleted successfully');
    exit();
} catch (Exception $e) {
    
    $conn->rollback();
    // Redirect to manage_users.php with an error message
    header('Location: manage_users.php?status=error&message=Failed to delete user: ' . urlencode($e->getMessage()));
    exit();
}
?>
