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

// Retrieve cart items for the logged-in user
$query = "SELECT cart.product_id, cart.quantity, products.name, products.price, products.stock, products.discount
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
}

// Initialize error messages array
$errors = [];

// Initialize total price
$total_price = 0;
$selected_cart_items = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items']; // Retrieve selected item IDs

    // Validate selected items
    foreach ($selected_items as $item_id) {
        foreach ($cart_items as $item) {
            if ($item['product_id'] == $item_id) {
                $selected_cart_items[] = $item; // Store selected items
                // Calculate discounted price
                $discounted_price = $item['price'] * (1 - ($item['discount'] / 100));
                $total_price += $discounted_price * $item['quantity'];
            }
        }
    }

    // Sanitize and validate fields
    $shipping_address = trim($_POST['shipping_address']);
    $contact_number = trim($_POST['contact_number']);
    $payment_method = $_POST['payment_method'];

    // Basic PHP validation
    if (empty($shipping_address) || strlen($shipping_address) < 10) {
        $errors[] = "Shipping address should be at least 10 characters.";
    }
    if (!preg_match('/^\+?\d{10,15}$/', $contact_number)) {
        $errors[] = "Enter a valid contact number with 10-15 digits.";
    }

    if (empty($errors)) {
        // Generate tracking number
        $tracking_number = uniqid('TRACK_');

        // Insert order details
        $insertOrderQuery = "INSERT INTO orders (user_email, tracking_number, shipping_address, contact_number, total_price, created_at, tracking_status)
                             VALUES (?, ?, ?, ?, ?, NOW(), 'Pending')";
        $stmt = $conn->prepare($insertOrderQuery);
        $stmt->bind_param("ssssd", $user_email, $tracking_number, $shipping_address, $contact_number, $total_price);
        $stmt->execute();

        // Update product stock based on the selected cart items
        foreach ($selected_cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity_ordered = $item['quantity'];

            // Deduct the ordered quantity from the product's stock
            $updateStockQuery = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
            $stmt = $conn->prepare($updateStockQuery);
            $stmt->bind_param("iii", $quantity_ordered, $product_id, $quantity_ordered);
            $stmt->execute();
        }

        // Clear selected items from the cart after checkout
        $clearCartQuery = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        foreach ($selected_items as $selected_id) {
            $stmt = $conn->prepare($clearCartQuery);
            $stmt->bind_param("si", $user_email, $selected_id);
            $stmt->execute();
        }

        // Redirect to place_order.php with tracking number and total price
        header("Location: place_order.php?tracking_number=$tracking_number&total_price=" . urlencode(number_format($total_price, 2)));
        exit();
    }
}

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
        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .checkout-button, .cancel-button {
            width: 100%;
            padding: 10px;
            color: #f5eff4;
            background-color: #6f0936;
            border: none;
            border-radius: 0px;
            cursor: pointer;
            letter-spacing: 0.8px;
            font-size: 14px;
            font-family: Lato, sans-serif;
            transition: background-color 0.3s, color 0.3s ease;
            margin-bottom: 10px;
        }
        .checkout-button:hover, .cancel-button:hover {
            background-color: #f5eff4;
            color: #6f0936;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Checkout</h2>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="cart-items">
            <h3>Your Selected Items</h3>
            <form method="post" action="checkout.php">
                <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $item): ?>
                        <?php
                        $item_discounted_price = $item['price'] * (1 - ($item['discount'] / 100));
                        ?>
                        <label>
                            <input type="checkbox" name="selected_items[]" value="<?php echo htmlspecialchars($item['product_id']); ?>" onchange="updateTotal()">
                            <?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>) - $<span class="item-price"><?php echo number_format($item_discounted_price, 2); ?></span>
                        </label><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No items in your cart.</p>
                <?php endif; ?>

                <p><strong>Total: $<span id="total-price"><?php echo number_format($total_price, 2); ?></span></strong></p>

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
                        <option value="">Select Payment Method</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <button type="submit" class="checkout-button">Place Order</button>
                <button type="button" class="cancel-button" onclick="window.location.href='cart.php'">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function updateTotal() {
            let total = 0;
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
            checkboxes.forEach(checkbox => {
                const itemPrice = parseFloat(checkbox.parentElement.querySelector('.item-price').innerText);
                const quantity = parseInt(checkbox.value);
                total += itemPrice * quantity;
            });
            document.getElementById('total-price').innerText = total.toFixed(2);
        }
    </script>
</body>
</html>
