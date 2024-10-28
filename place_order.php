<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the tracking number and total price from URL parameters
$tracking_number = isset($_GET['tracking_number']) ? $_GET['tracking_number'] : '';
$total_price = isset($_GET['total_price']) ? $_GET['total_price'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-image:url('images/place-order.jpg');
            background-size: cover;       
    background-repeat: no-repeat; 
    background-position: center;  
    background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .confirmation-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 48px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            text-align: center;
            width: 100%;
            max-width: 450px;
            height:200px;
            opacity: 0.8;
            display: flex;
            flex-direction: column; 
            justify-content: space-between;
        }
         
    
        h2 {
            color: #6f0936;
            margin-bottom: 20px;
        }
        p {
            margin: 5px 0;
            font-size: 16px;
        }
        .back-to-home-button {
            width: 100%;
            padding: 10px;
            background-color: #6f0936;
            color: #f5eff4;
            border: 1px solid #6f0936;
            border-radius: 0px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            font-family: Lato, sans-serif;
            transition: background-color 0.3s, color 0.3s ease, border 0.3s ease;
            align-self: flex-end;
             margin-top: auto;
        }
        .back-to-home-button:hover {
           background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <h2>Thank you for your order!</h2>
        <p>Your tracking number is: <strong><?php echo htmlspecialchars($tracking_number); ?></strong></p>
        <p>Total Price: <strong>$<?php echo htmlspecialchars($total_price); ?></strong></p>
<form action="index.php" method="get">
    <button type="submit" class="back-to-home-button">Back to Home</button>
</form>   
 </div>
</body>
</html>
