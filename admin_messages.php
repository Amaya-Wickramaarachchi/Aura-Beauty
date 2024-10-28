<?php
// Start session (assuming admin login functionality)
session_start();

require 'connection.php';

// Fetch all messages from the database
$sql = "SELECT * FROM customer_messages ORDER BY created_at DESC";
$result = $conn->query($sql);

// Handle response submission
$responseMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send-response'])) {
    $customerEmail = $_POST['email'];
    $adminResponse = $_POST['response'];

    // Validate response - STILL DEVELOPING
    if (!empty($adminResponse)) {
        // Set up email headers
        $to = $customerEmail;
        $subject = "Response to your message - Aura Beauty";
        $message = $adminResponse;
        $headers = "From: no-reply@aurabeauty.com\r\n";
        $headers .= "Reply-To: admin@aurabeauty.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 

        // Send the email using PHP's mail() function
        if (mail($to, $subject, $message, $headers)) {
            $responseMessage = "Response sent successfully to $customerEmail.";
        } else {
            $responseMessage = "Failed to send the response. Please try again.";
        }
    } else {
        $responseMessage = "Response cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">
    <title>Admin - View and Respond to Messages</title>
    <style>
        body {
            font-family: 'Lato', sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            padding: 20px;
            margin-top: 0;
            background-color: #6F0936;
            color: white;
            border-bottom: 3px solid #d75c8c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f5eff4;
            color: #6f0936;
        }
        .message-details {
            margin: 20px 0;
        }
        .response-form {
            margin: 20px 0;
            padding: 15px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
        }
        textarea {
            width: 98%;
            padding: 10px;
            margin-bottom: 10px;
        }
       
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
        button {
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
        }

        button:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
        .button-container button{
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
            margin-top:10px;
        }
        .button-container button:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
    </style>
</head>
<body>

    <h1>Admin - View Customer Messages</h1>

    <?php if (!empty($responseMessage)): ?>
        <p class="<?php echo strpos($responseMessage, 'successfully') !== false ? 'success-message' : 'error-message'; ?>">
            <?php echo htmlspecialchars($responseMessage); ?>
        </p>
    <?php endif; ?>

    <!-- Table displaying all customer messages -->
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <!-- Button to respond to the message -->
                    <form action="#respond" method="POST">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                        <input type="hidden" name="message" value="<?php echo htmlspecialchars($row['message']); ?>">
                        <button type="submit" name="reply">Respond</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Form to send a response -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply'])): ?>
        <div id="respond" class="response-form">
            <h2>Respond to Message</h2>
            <p>Customer Email: <?php echo htmlspecialchars($_POST['email']); ?></p>
            <p>Original Message: <?php echo htmlspecialchars($_POST['message']); ?></p>

            <form action="admin_messages.php" method="POST">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>">
                <textarea name="response" rows="5" placeholder="Write your response here..." required></textarea>
                <button type="submit" name="send-response">Send Response</button>
            </form>
        </div>
        
    <?php endif; ?>
    <div class="button-container">
    <button type="button" onclick="window.location.href='admin_dashboard.php'">B A C K  T O  D A S H B O A R D</button>
    <button type="button" onclick="window.location.href='index.php'">H O M E</button>
</div>

</body>
</html>
