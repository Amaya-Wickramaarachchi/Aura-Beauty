<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// Initialize variables for cart items
$cart_items = [];
$total_price = 0;

// Retrieve cart items for the logged-in user
$query = "SELECT cart.product_id, cart.quantity, products.name, products.price
          FROM cart
          JOIN products ON cart.product_id = products.id
          WHERE cart.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch cart items
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['shipping_address'], $_POST['contact_number'], $_POST['payment_method'])) {
        $shipping_address = $_POST['shipping_address'];
        $contact_number = $_POST['contact_number'];
        $payment_method = $_POST['payment_method'];

        // Generate tracking number
        $tracking_number = uniqid('TRACK_');

        // Insert order details
        $insertOrderQuery = "INSERT INTO orders (user_email, tracking_number, shipping_address, contact_number, total_price, created_at, tracking_status)
                             VALUES (?, ?, ?, ?, ?, NOW(), 'Pending')";
        $stmt = $conn->prepare($insertOrderQuery);
        $stmt->bind_param("ssssd", $user_email, $tracking_number, $shipping_address, $contact_number, $total_price);
        $stmt->execute();

        // Clear cart after checkout
        $clearCartQuery = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($clearCartQuery);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();

        // Redirect to place_order.php with tracking number and total price
        header("Location: place_order.php?tracking_number=$tracking_number&total_price=" . urlencode(number_format($total_price, 2)));
        exit();
    }
}

// HTML output starts here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .checkout-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
        }
        h2 {
            color: #6f0936;
            text-align: center;
            margin-bottom: 25px;
        }
        .cart-items {
            margin-bottom: 20px;
        }
        .cart-items p {
            margin: 0;
            font-size: 14px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }
        input[type="text"], select {
        
           width: 100%;
            padding: 15px;
            color: #6f0936;
            outline: none;
            font-size: 14px;
            background-color: #ffffff;
            border: 1px solid #ffffff;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
        }
        
        .checkout-button {
            width: 100%;
            padding: 10px;
            background-color: #6f0936;
            color: #f5eff4;
            border: 1px solid #6f0936;
            border-radius: 0px;
            cursor: pointer;
            letter-spacing:0.8px;
            font-size: 14px;
            font-family: Lato, sans-serif;
            transition: background-color 0.3s, color 0.3s ease, border 0.3s ease;
        }
        .checkout-button:hover {
             background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>

        <div class="cart-items">
            <h3>Your Selected Items</h3>
            <?php foreach ($cart_items as $item): ?>
                <p><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>) - $<?php echo number_format($item['price'], 2); ?></p>
            <?php endforeach; ?>
            <p><strong>Total: $<?php echo number_format($total_price, 2); ?></strong></p>
        </div>

        <form method="post" action="checkout.php">
            <div class="form-group">
                <label for="shipping_address">Shipping Address:</label>
                <input type="text" id="shipping_address" name="shipping_address" required>
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <button type="submit" class="checkout-button">Place Order</button>
        </form>
    </div>
</body>
</html>
