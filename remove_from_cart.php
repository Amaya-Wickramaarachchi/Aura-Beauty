<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    echo 'Unauthorized';
    exit;
}

$item_id = $_POST['item_id'] ?? null;

if ($item_id) {
    // Output for debugging
    error_log("Item ID: $item_id");

    // Database connection
    $host = 'localhost';
    $db = 'aura_beauty';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        echo 'Database error';
        exit;
    }

    // Delete item from the cart for the user
    $stmt = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
    $stmt->execute([$item_id, $_SESSION['user_email']]);

    echo 'Item removed successfully';
} else {
    echo 'Invalid request';
}
