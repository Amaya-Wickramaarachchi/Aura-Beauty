<?php
session_start();
require 'connection.php'; 

// Ensure only admins can access this page
if (!isset($_SESSION['user_email']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

// Check if a user email is provided in the query parameter
if (!isset($_GET['email'])) {
    header('Location: manage_users.php?status=error&message=' . urlencode("No user selected."));
    exit();
}

$email = $_GET['email'];
$message = '';

// Fetch user data from the database
$stmt = $conn->prepare('SELECT * FROM user WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: manage_users.php?status=error&message=' . urlencode("User not found."));
    exit();
}

$user = $result->fetch_assoc();

// Update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Prepare the update query
    $stmt = $conn->prepare('UPDATE user SET first_name = ?, last_name = ?, birthday = ?, gender = ?, contact_number = ?, address = ?, is_admin = ? WHERE email = ?');
    $stmt->bind_param('ssssssis', $first_name, $last_name, $birthday, $gender, $contact_number, $address, $is_admin, $email);

    if ($stmt->execute()) {
        // Redirect to manage_users.php with a success message
        header('Location: manage_users.php?status=success&message=' . urlencode("User details updated successfully."));
        exit();
    } else {
        $message = "Failed to update user details.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            padding: 20px;
            margin-top: 0;
            background-color: #6F0936;
            color: white;
            border-bottom: 3px solid #d75c8c;
        }
        select {
            padding: 5px;
            width:100%;
            border-radius: 5px;
        }
        button {
            width:100%;
            padding: 10px;
            text-align: center;
            background-color: #6f0936;
            color: #fff;
            margin-top:5px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 1px;
            transition: background-color 0.3s;
        }
        a {
            padding: 10px;
            text-align: center;
            background-color: #6f0936;
            color: #fff;
            margin-top:5px;
            text-decoration: none;
            font-weight: bold;
            border-radius: 1px;
            transition: background-color 0.3s;
        }
        .message {
            font-weight: bold;
            text-align: center;
            color: green;
        }
        .error {
            color: red;
        }
        input[type="text"],
        input[type="number"],input[type="date"],textarea {
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
        input[type="checkbox"]{
            width: 5%;
            padding: 5px;
            color: #6f0936;
            outline: none;
            font-size: 14px;
            background-color: #ffffff;
            border: 1px solid #ffffff;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h1>Edit User</h1>

    <!-- Display success or error message -->
    <?php if ($message): ?>
        <p class="message <?php echo strpos($message, 'Failed') === false ? '' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <!-- Edit User Form -->
    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required><br>

        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>"><br>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select><br>

        <label for="contact_number">Contact Number:</label>
        <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" required><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address" required><?php echo htmlspecialchars($user['address']); ?></textarea><br>

        <label for="is_admin">Admin:</label>
        <input type="checkbox" id="is_admin" name="is_admin" <?php if ($user['is_admin']) echo 'checked'; ?>><br>

        <button type="submit">Update User</button>
        
        <button type="button" onclick="window.location.href='manage_users.php'" >Cancel</button>
    </form>
</body>
</html>
