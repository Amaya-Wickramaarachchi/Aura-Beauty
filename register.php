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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $first_name = $conn->real_escape_string($first_name);
    $last_name = $conn->real_escape_string($last_name);
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $checkEmail = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        // Email already exists
        echo "<script>alert('Oops! It seems that the email you entered is already registered. Please try logging in or use a different email.'); window.location.href='register.php';</script>";
    } else {
        $sql = "INSERT INTO user (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to login page
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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
        body {
            font-family: 'Lato', sans-serif;
            background-color:#FDF5F1;
            margin: 0;
            padding:0;
        }

        .container {
            width:auto;
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
            color: #6f0936;
            outline: none;
            background-color:#FDF5F1;
            font-size: 14px;
            color: #6f0936;
            border: 1px solid #FDF5F1;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom:15px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #6f0936;
            color: #fff;
            border: 1px solid #6f0936;
            border-radius: 0px;
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
        p {
            color: #6f0936;
            font-size: medium;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>
        <form action="register.php" method="post">
            
            <input type="text" id="first_name"placeholder="FIRST NAME" name="first_name" required>

            <input type="text" id="last_name" placeholder="LAST NAME" name="last_name" required>
            
            <input type="email" id="email" placeholder="EMAIL" name="email" required>

            <input type="password" id="password"placeholder="PASSWORD" name="password" required>

            <input type="submit" value="R E G I S T E R">

            <p>* By clicking "Register", I agree to Aura Beauty's Terms of Service and acknowledge that I have read its Privacy Policy.</p>
            <p>Already have an account? <a href="login.php" class="btn" style="color: #6f0936;">Login here.</a></p>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>


