<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php'); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <style>
        body {
            font-family: 'Lato', sans-serif;
           background-color: #fdfbf8;
    margin: 0;
        }

        .dashboard-container {
            width: auto;
            max-width:800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #6f0936;
            font-size: 2rem;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }

        .menu a {
            padding: 15px;
            text-align: center;
            background-color: #6f0936;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu a:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>

        <div class="menu">
            <a href="manage_orders.php">Manage Orders</a>
            <a href="manage_users.php">Manage Users</a>
            <a href="manage_products.php">Manage Products</a>
            <a href="admin_messages.php">Customer Messages</a>
            <a href="manage_faq.php">Manage FAQs</a>
            <a href="index.php">HOME</a>
        </div>
    </div>

</body>
</html>
