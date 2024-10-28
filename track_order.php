<?php
session_start();
include 'connection.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    unset($_SESSION['order_result']);
}

?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5eff4;
        }
        h1 {
            margin-top: 200px;
            text-align: center;
            color: #6f0936;
        }
        form {
            text-align: center;
            margin: 20px;
        }
        label {
            font-size: 16px;
            color: #333;
            margin-right: 10px;
        }
        input[type="text"] {
            width: 50%;
            padding: 15px;
            color: #6f0936;
            outline: none;
            background-color: #f5eff4;
            font-size: 14px;
            color: #6f0936;
            border: 1px solid #f5eff4;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            width: 10%;
            padding: 15px;
            background-color: #6f0936;
            color: #fff;
            border: none;
            border-radius: 0px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bolder;
            transition: background-color 0.3s, color 0.3s, border 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 2px solid #6f0936;
        }

        .result {
            text-align: center;
            margin: 20px;
        }

        .status-icon {
            font-size: 50px;
            margin-right: 10px;
        }
    </style>
</head>

<body>



<h1>Track Your Order</h1>
<form method="POST" action="track_order.php">
    <input type="text" id="tracking_number" placeholder="TRACKING NUMBER" name="tracking_number" required>
    <input type="submit" value="Track Order">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tracking_number'])) {
    $tracking_number = $_POST['tracking_number'];

    // Query to fetch order details based on tracking number
    $stmt = $conn->prepare("SELECT order_id, tracking_status FROM orders WHERE tracking_number = ?");
    $stmt->bind_param("s", $tracking_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch order details
        $order = $result->fetch_assoc();
        $_SESSION['order_result'] = $order;
        echo "<div class='result'>";
        echo "<h2>Order ID: " . htmlspecialchars($order['order_id']) . "</h2>";

        // Display tracking status with icons
        switch ($order['tracking_status']) {
            case 'Pending':
                echo "<i class='fas fa-hourglass-half status-icon' style='color: orange;'></i>
                      <p><strong>Status:</strong> Pending</p>
                      <p>Your order has been received and is currently awaiting processing. We’ll keep you updated!</p>";
                break;

            case 'Processing':
                echo "<i class='fas fa-cogs status-icon' style='color: blue;'></i>
                      <p><strong>Status:</strong> Processing</p>
                      <p>Your order is being prepared for shipment. Thank you for your patience!</p>";
                break;

            case 'Transit':
                echo "<i class='fas fa-plane status-icon' style='color: green;'></i>
                      <p><strong>Status:</strong> In Transit</p>
                      <p>Your order is on its way to your delivery address. It should arrive soon!</p>";
                break;

            case 'Shipped':
                echo "<i class='fas fa-truck status-icon' style='color: blue;'></i>
                      <p><strong>Status:</strong> Shipped</p>
                      <p>Your order has been shipped! It's currently on the road to you.</p>";
                break;

            case 'Delivered':
                echo "<i class='fas fa-check-circle status-icon' style='color: green;'></i>
                      <p><strong>Status:</strong> Delivered</p>
                      <p>Your order has been successfully delivered! We hope you enjoy your purchase!</p>";
                break;

            case 'Cancelled':
                echo "<i class='fas fa-times-circle status-icon' style='color: red;'></i>
                      <p><strong>Status:</strong> Cancelled</p>
                      <p>We're sorry, but your order has been cancelled and will not be fulfilled.</p>";
                break;

            case 'Unknown':
                echo "<i class='fas fa-exclamation-triangle status-icon' style='color: red;'></i>
                      <p><strong>Status:</strong> Unknown</p>
                      <p>We couldn't track your order at this time. Please contact customer support for assistance.</p>";
                break;

            default:
                echo "<i class='fas fa-hourglass-half status-icon' style='color: orange;'></i>
                      <p><strong>Status:</strong> Pending</p>
                      <p>Your order has been received and is currently awaiting processing. We’ll keep you updated!</p>";
                break;
        }
        echo "</div>";
    } else {
        echo "<div class='result'><p class='result'>No order found with that tracking number. Please double-check and try again.</p></div>";
    }
}
?>

<?php include 'footer.php'; ?>

</body>
</html>
