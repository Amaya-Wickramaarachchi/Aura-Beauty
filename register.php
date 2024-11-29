<?php

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "aura_beauty";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'password' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (empty($first_name) || !preg_match("/^[a-zA-Z]+$/", $first_name)) {
        $errors['first_name'] = "Please enter a valid first name (letters only).";
    }
    if (empty($last_name) || !preg_match("/^[a-zA-Z]+$/", $last_name)) {
        $errors['last_name'] = "Please enter a valid last name (letters only).";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    }
    if (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    }

    if (!array_filter($errors)) {
        // Check if email exists
        $checkEmail = "SELECT * FROM user WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
            $errors['email'] = "The email is already registered. Please try logging in or use a different email.";
        } else {
            // Insert into database
            $sql = "INSERT INTO user (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
ob_end_flush();
?>

<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <style>
        /* Your existing styles */
        body {
            font-family: 'Lato', sans-serif;
            background-color: #FDF5F1;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 150px auto;
            padding: 20px;
            background-color: #FDF5F1;
        }
        h1 {
            font-size: xx-large;
            color: #6f0936;
            text-align: center;
        }
        form {
            margin-top: 30px;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 15px;
            outline: none;
            background-color: #FDF5F1;
            font-size: 14px;
            color: #6f0936;
            border: 1px solid #FDF5F1;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 5px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #6f0936;
            color: #fff;
            border: 1px solid #6f0936;
            cursor: pointer;
            font-size: 12px;
            font-weight: bolder;
            transition: background-color 0.3s, color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-bottom: 10px;
        }
        p {
            color: #6f0936;
            font-size: medium;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("form");
            
            form.addEventListener("submit", function (e) {
                let isValid = true;
                
                // Reset previous error messages
                document.querySelectorAll('.error-message').forEach(el => el.innerText = '');

                // Client-side validation
                const firstName = document.getElementById("first_name").value.trim();
                const lastName = document.getElementById("last_name").value.trim();
                const email = document.getElementById("email").value.trim();
                const password = document.getElementById("password").value.trim();

                if (!/^[a-zA-Z]+$/.test(firstName)) {
                    document.getElementById("first_name_error").innerText = "Please enter a valid first name (letters only).";
                    isValid = false;
                }
                if (!/^[a-zA-Z]+$/.test(lastName)) {
                    document.getElementById("last_name_error").innerText = "Please enter a valid last name (letters only).";
                    isValid = false;
                }
                if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
                    document.getElementById("email_error").innerText = "Please enter a valid email address.";
                    isValid = false;
                }
                if (password.length < 8) {
                    document.getElementById("password_error").innerText = "Password must be at least 8 characters long.";
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>
        <form action="register.php" method="post">
            <input type="text" id="first_name" placeholder="FIRST NAME" name="first_name" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
            <div id="first_name_error" class="error-message"><?php echo $errors['first_name']; ?></div>

            <input type="text" id="last_name" placeholder="LAST NAME" name="last_name" required value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
            <div id="last_name_error" class="error-message"><?php echo $errors['last_name']; ?></div>

            <input type="email" id="email" placeholder="EMAIL" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <div id="email_error" class="error-message"><?php echo $errors['email']; ?></div>

            <input type="password" id="password" placeholder="PASSWORD" name="password" required>
            <div id="password_error" class="error-message"><?php echo $errors['password']; ?></div>

            <input type="submit" value="R E G I S T E R">
            <p>* By clicking "Register", I agree to Aura Beauty's Terms of Service and acknowledge that I have read its <a href="privacy_policy.php" class="btn" style="color: #6f0936;">Privacy Policy.</a></p>
            <p>Already have an account? <a href="login.php" class="btn" style="color: #6f0936;">Login here.</a></p>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
