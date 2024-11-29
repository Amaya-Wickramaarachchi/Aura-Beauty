<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$host = 'localhost';
$db = 'aura_beauty';
$user = 'root';
$pass = '';

// Create a MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user's email
$user_email = $_SESSION['user_email'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // Delete item from the cart table
    $stmt = $conn->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
    $stmt->bind_param('is', $item_id, $user_email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo 'Item removed successfully';
    } else {
        echo 'Failed to remove item';
    }

    $stmt->close();
    exit(); 
}

// Fetch cart items for the user
$cartItems = [];
// Fetch cart items for the user, including discount calculation
if ($user_email) {
    $stmt = $conn->prepare('
        SELECT 
            c.id, 
            p.name, 
            p.price, 
            p.discount,
            c.quantity,
            CASE 
                WHEN p.discount > 0 
                THEN p.price * (1 - p.discount / 100) 
                ELSE p.price 
            END AS final_price 
        FROM 
            cart c 
            JOIN products p ON c.product_id = p.id 
        WHERE 
            c.user_id = ?');
    $stmt->bind_param('s', $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Function to calculate total price
function calculateTotal($items) {
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

$total = calculateTotal($cartItems);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f9f9f9;
            margin-top: 20px;
            padding: 20px;
            color: #333;
        }

        h1 {
            padding: 50px;
            margin-top: 140px;
            font-size: 2.5em;
            color: #6f0936; 
        }

        .cart-table {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 0px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #6f0936;
            color: white;
        }

        .cart-table tr:hover {
            background-color: #f1f1f1;
        }

       .checkout-button {
            width: 100%;
            max-width:1200px;
            margin: 20px 65px auto;
            margin-bottom:50px;
            padding: 10px 10px;
            background-color: white;
            color: #6F0936;
            border: 1px solid #6F0936;
            border-radius: 0; 
            text-align: center;
            letter-spacing: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .checkout-button:hover {
            background-color: #6f0936;
            color: #f5eff4;
        }

        .total {
            font-weight: bold;
            font-size: 1.5em;
            text-align: right;
            margin-right: 70px;
            color: #6f0936;
        }

        .remove-icon {
            color: #6f0936;
            cursor: pointer;
            font-size: 1.5em;
            transition: color 0.3s;
        }

        .remove-icon:hover {
            color: #6f0936;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            font-size: 1.2em;
            color: #6f0936;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1>Your Cart</h1>
<form id="checkoutForm" method="POST" action="checkout.php">
    <input type="hidden" name="selected_items" id="selectedItems" value="">
    <table class="cart-table">
        <thead>
            <tr>
                <th>Select</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    <?php if (count($cartItems) > 0): ?>
        <?php foreach ($cartItems as $item): ?>
            <tr id="item-<?php echo $item['id']; ?>">
                <td>
                    <input type="checkbox" class="item-checkbox" data-price="<?php echo $item['final_price']; ?>" data-quantity="<?php echo $item['quantity']; ?>" value="<?php echo $item['id']; ?>" onchange="updateTotal()">
                </td>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td>
                    <?php if ($item['discount'] > 0): ?>
                        <span style="text-decoration: line-through; color: #999;">$<?php echo number_format($item['price'], 2); ?></span>
                        <span style="color: #6f0936;">$<?php echo number_format($item['final_price'], 2); ?></span>
                    <?php else: ?>
                        $<?php echo number_format($item['price'], 2); ?>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td>
                    <i class="fas fa-trash remove-icon" title="Remove from cart" onclick="removeFromCart(<?php echo $item['id']; ?>);"></i>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">Your cart is empty! <a href="products.php">Continue Shopping</a></td>
        </tr>
    <?php endif; ?>
</tbody>

    </table>

    <div class="total" id="totalPrice">Total: $<?php echo number_format($total, 2); ?></div>
    <div class="loading-spinner" id="loadingSpinner">Processing...</div>

    <button type="submit" class="checkout-button" onclick="prepareSelectedItems(event)">Checkout</button>
</form>

<script>
function updateTotal() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    let total = 0;
    checkboxes.forEach(checkbox => {
        const price = parseFloat(checkbox.getAttribute('data-price'));
        const quantity = parseInt(checkbox.getAttribute('data-quantity'));
        total += price * quantity;
    });
    document.getElementById('totalPrice').innerText = 'Total: $' + total.toFixed(2);
}


function prepareSelectedItems(event) {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    const selectedIds = Array.from(checkboxes).map(checkbox => checkbox.value);

    if (selectedIds.length === 0) {
        event.preventDefault();
        alert('No items selected for checkout!');
    } else {
        document.getElementById('selectedItems').value = selectedIds.join(',');
    }
}

function confirmRemoveItem(itemId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        removeFromCart(itemId);
    }
}

function removeFromCart(itemId) {
    document.getElementById('loadingSpinner').style.display = 'block';
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function () {
        document.getElementById('loadingSpinner').style.display = 'none';
        if (xhr.status === 200 && xhr.responseText === 'Item removed successfully') {
            const row = document.getElementById('item-' + itemId);
            if (row) {
                row.style.transition = "opacity 0.3s ease";
                row.style.opacity = 0;
                setTimeout(() => {
                    row.remove();
                    updateTotal();
                }, 300);
            }
        } else {
            alert('Error removing item: ' + xhr.responseText);
        }
    };

    xhr.onerror = function () {
        alert('Request failed');
    };

    xhr.send('item_id=' + itemId);
}

</script>

<?php include 'footer.php'; ?>

</body>
</html>
