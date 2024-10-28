<?php
session_start();
require 'connection.php'; 

// Check if the user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Initialize a message variable
$update_message = '';

// Update tracking status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $tracking_status = $_POST['tracking_status'];

    // Update the tracking status in the database
    $stmt = $conn->prepare('UPDATE orders SET tracking_status = ? WHERE order_id = ?');
    $stmt->bind_param('si', $tracking_status, $order_id);
    if ($stmt->execute()) {
        $update_message = 'Tracking status updated successfully!';
    } else {
        $update_message = 'Error updating tracking status.';
    }
    $stmt->close();
}

// Fetch all orders
$result = $conn->query('SELECT * FROM orders ORDER BY created_at DESC');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            padding: 20px;
            margin-top: 0;
            background-color: #6F0936;
            color: white;
            border-bottom: 3px solid #d75c8c;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .order-table th, .order-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .order-table th {
            background-color: #6f0936;
            color: #fff;
        }

        .update-form {
            display: inline-block;
            margin-left: 10px;
        }

        select {
            padding: 5px;
            width:100%;
            border-radius: 5px;
        }

        .btn-update {
            padding: 8px 12px;
            background-color: #6f0936;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top:10px;
            width:100%;
        }

        .btn-update:hover {
            background-color: #f0936;
        }

        .message {
            margin: 20px 0;
            color: green;
            font-weight: bold;
            text-align: center;
        }

        .error-message {
            color: red;
        }
        button {
            width:100%;
            background-color: #6f0936;
            color:white;
            margin-bottom: 10px;
            transition: background-color 0.3s;
            padding:10px;
            cursor:pointer;
        }
    </style>
</head>
<body>
    <h1>Order Management</h1>

<div class="container">
   

    <?php if (!empty($update_message)): ?>
        <p class="message"><?php echo $update_message; ?></p>
    <?php endif; ?>

    <table class="order-table">
        <tr>
            <th>Order ID</th>
            <th>User Email</th>
            <th>Tracking Number</th>
            <th>Shipping Address</th>
            <th>Contact Number</th>
            <th>Total Price</th>
            <th>Created At</th>
            <th>Tracking Status</th>
            <th>Update Status</th>
        </tr>
        <?php while ($order = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo $order['user_email']; ?></td>
                <td><?php echo $order['tracking_number']; ?></td>
                <td><?php echo $order['shipping_address']; ?></td>
                <td><?php echo $order['contact_number']; ?></td>
                <td><?php echo $order['total_price']; ?></td>
                <td><?php echo $order['created_at']; ?></td>
                <td><?php echo $order['tracking_status']; ?></td>
                <td>
                    <form action="manage_orders.php" method="post" class="update-form">
                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                        <select name="tracking_status">
                            <option value="Pending" <?php if ($order['tracking_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Processing" <?php if ($order['tracking_status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                            <option value="Transit" <?php if ($order['tracking_status'] == 'Transit') echo 'selected'; ?>>In Transit</option>
                            <option value="Shipped" <?php if ($order['tracking_status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="Delivered" <?php if ($order['tracking_status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                            <option value="Cancelled" <?php if ($order['tracking_status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit" class="btn-update">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <button type="button" onclick="window.location.href='admin_dashboard.php'" >Back to Dashboard</button>
    <button type="button" onclick="window.location.href='index.php'" >HOME</button>
</div>

</body>
</html>
<?php
// Close the database connection
$conn->close();
