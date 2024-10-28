<?php
// Start session
session_start();

// Include database connection
require 'connection.php';

// Initialize variables
$messageSent = false;
$errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Simple form validation
    if (empty($name) || empty($email) || empty($message)) {
        $errorMessage = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    } else {
        // Insert message into the database
        $stmt = $conn->prepare("INSERT INTO customer_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $messageSent = true;
        } else {
            $errorMessage = "There was an issue submitting your message. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <style>
        body {
    font-family: 'Lato', sans-serif;
    background: url('https://img.freepik.com/free-photo/top-view-spa-items-white-background_23-2148268398.jpg?t=st=1729693322~exp=1729696922~hmac=b27bad524704f48571eadadb85488d3685c3a275a70975d85d9c9db5be994f45&w=740') no-repeat center center fixed; 
    background-size: cover; 
    padding: 20px; 
    text-align: center;
    
}
        h1 {
            text-align: center;
            color: #6F0936;
        }
        .contact-form, .thank-you {
            max-width: 600px;
            height:auto;
            max-height:600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius:5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        button {
             width: 100%;
            padding: 10px;
            background-color: #6f0936;
            color: #f5eff4;
            border: 1px solid #6f0936;
            border-radius: 0px;
            cursor: pointer;
            letter-spacing:0.8px;
            font-size: 14px;
            font-family: Lato, sans-serif;
            transition: background-color 0.3s, color 0.3s ease, border 0.3s ease;
        }

        button:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
        .error {
            color: red;
            text-align: center;
        }
        p{
            font-size:larger;
            text-align:center;
        }
        .thank-you-image-container {
    display: flex;
    justify-content: center; 
    align-items: center; 
    margin: 20px; 
}

.thank-you-image {
    max-width: 100%;
    max-height: 300px; 
    
    transition: transform 0.3s; 
}


    </style>
</head>
<body>

<?php if ($messageSent): ?>
    <!-- Thank You Message -->
    <div class="thank-you">
        <h1>Thank You!</h1>
        <p>Your message has been sent successfully.</br>We will get back to you soon.</p>
        <div class="thank-you-image-container">
    <img src="https://img.freepik.com/free-vector/heart-shaped-bunch-red-roses-realistic-illustration_1284-55756.jpg?t=st=1729444347~exp=1729447947~hmac=5da8b688628942efd63d358383c00f84a7d9cb451ce76122ade76c49eb13b834&w=740" alt="Thank You" class="thank-you-image">
</div>

        <button onclick="window.location.href='index.php';">BACK TO HOME</button>
    </div>
<?php else: ?>   
        <?php if (!empty($errorMessage)): ?>
            <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
<?php endif; ?>

</body>
</html>
