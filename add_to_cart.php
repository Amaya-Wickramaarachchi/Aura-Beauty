<?php
session_start();
require 'connection.php'; 

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// Retrieve product ID and quantity from the form
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Use the user's email to fetch the email directly
$user_email = $_SESSION['user_email'];

try {
    // Prepare the insert statement for the cart
    $query = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, ?, NOW())");

    
    $query->bind_param("ssi", $user_email, $product_id, $quantity);
    
    // Execute the query
    if ($query->execute()) {
        $_SESSION['message'] = 'Product has been added to the cart!';
    } else {
        $_SESSION['message'] = 'There was an error adding the product to the cart.';
    }
    $query->close();
} catch (Exception $e) {
    $_SESSION['message'] = 'There was an error adding the product to the cart: ' . $e->getMessage();
}


header("Location: product.php?id=$product_id");
exit();
?>
