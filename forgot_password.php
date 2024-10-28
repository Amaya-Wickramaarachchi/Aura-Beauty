<?php
require 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if new password and confirm password match
    if ($new_password !== $confirm_password) {
        echo "<p style='color: red;'>Passwords do not match. Please try again.</p>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Update the password 
            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $new_password, $email);
            $stmt->execute();

            // Redirect to login with success message
            echo "<p style='color: green;'>Your password has been updated successfully. Please <a href='login.php'>login</a> using your new password.</p>";
            header("Location: login.php?message=Password updated successfully. Please login with your new password.");
            exit();
        } else {
            echo "<p style='color: red;'>No account found with that email.</p>";
        }
    }
}
?>

<!-- HTML Form for resetting the password -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <style>
    body {
            font-family: 'Lato', sans-serif;
            margin: 0;
            padding: 0;
          
        }

    
    h2 {
         font-size: xx-large;
            color: #6f0936;
            text-align: center;
    }
    
    input[type="email"], input[type="password"] {
        width: 100%;
            padding: 15px;
            color: #6f0936;
            outline: none;
            font-size: 14px;
            background-color: rgba(250, 246, 249, 1);
            border: 1px solid rgba(250, 246, 249, 1);
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
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
    
        form {
            margin-top: 20px;
        }
    .form-container {
             max-width: 500px;
            margin: 150px auto;
            padding: 20px;
            background-color: rgba(250, 246, 249, 1);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

</head>
<body>

<?php include 'header.php'; ?>


<div class="form-container">
    <h2>Reset Password</h2>
    <form method="POST" action="forgot_password.php">
        
        <input type="email" name="email" placeholder="ENTER YOUR EMAIL" required>
        
       
        <input type="password" name="new_password" placeholder="ENTER NEW PASSWORD" required>
        
        
        <input type="password" name="confirm_password" placeholder="RE-ENTER NEW PASSWORD" required>
        
        <input type="submit" value="RESET PASSWORD">
    </form>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
