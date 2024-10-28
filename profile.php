<?php
session_start();
require 'connection.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php'); 
    exit();
}

// Database connection settings
$host = 'localhost';
$db = 'aura_beauty';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Establish database connection
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch user details from the database
$email = $_SESSION['user_email'];
$stmt = $pdo->prepare('SELECT first_name, last_name, birthday, gender, contact_number, address, is_admin FROM user WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

// Initialize message variable
$message = '';

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);

    // Update user profile in the database
    $update_stmt = $pdo->prepare('UPDATE user SET first_name = ?, last_name = ?, birthday = ?, gender = ?, contact_number = ?, address = ? WHERE email = ?');
    
    if ($update_stmt->execute([$first_name, $last_name, $birthday, $gender, $contact_number, $address, $email])) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile. Please try again.";
    }
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $delete_stmt = $pdo->prepare('DELETE FROM user WHERE email = ?');
    if ($delete_stmt->execute([$email])) {
        session_destroy(); 
        header('Location: goodbye.php'); 
    } else {
        $message = "Error deleting account. Please try again.";
    }
}

// Fetch user's order history
$order_stmt = $pdo->prepare('SELECT order_id, tracking_number, shipping_address, contact_number, total_price, created_at FROM orders WHERE user_email = ? ORDER BY created_at DESC');
$order_stmt->execute([$email]);
$orders = $order_stmt->fetchAll();
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap">
    <link rel="stylesheet" href="css/profile.css">

    <title>User Profile</title>
    
</head>


<body>

    <div class="profile-container">
        <h1>Welcome to Your Profile!</h1>

        <!-- Display success or error message -->
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Profile Update Form -->
        <form action="profile.php" method="POST">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
            </div>
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>">
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select name="gender">
                    <option value="Male" <?php echo $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo $user['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="tel" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" >
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>
            <button type="submit" name="update_profile" class="profile-btn">UPDATE PROFILE</button>
            

        </form>
        <form action="profile.php" method="POST" onsubmit="return confirmDelete();">
    <button type="submit" name="delete_account" class="delete-btn">DELETE ACCOUNT</button>
</form>
        <!-- Track Order Button -->
        <form action="track_order.php" method="GET" style="margin-top: 20px;">
    <input type="submit" value="TRACK ORDER" class="track-order-btn">
</form>

        <!-- Admin Dashboard button -->
        <?php if ($user['is_admin']): ?>
            <a href="admin_dashboard.php"><button class="admin-btn">ADMIN DASHBOARD</button></a>
        <?php endif; ?>

        <!-- Logout button -->
        <a href="logout.php"><button class="logout-btn">LOGOUT</button></a>

        <!-- Order History -->
        <div class="order-history-container">
            <h2>Order History</h2>
            <?php if (count($orders) > 0): ?>
                <table class="order-history">
                    <tr>
                        <th>Order ID</th>
                        <th>Tracking Number</th>
                        <th>Shipping Address</th>
                        <th>Contact Number</th>
                        <th>Total Price</th>
                        <th>Date</th>
                    </tr>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['tracking_number']); ?></td>
                            <td><?php echo htmlspecialchars($order['shipping_address']); ?></td>
                            <td><?php echo htmlspecialchars($order['contact_number']); ?></td>
                            <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
    function confirmDelete() {
        return confirm("Are you sure you want to delete your account? This action cannot be undone.");
    }
</script>
    
</body>
<?php include 'footer.php';
    ?>
</html>
