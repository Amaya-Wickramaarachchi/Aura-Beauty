<?php
// Start the session (if needed)
session_start();

// Include header file
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Aura Beauty</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <style>
        body {
            font-family: 'Lato', sans-serif;
            margin: 0;
           
            background-color: #f0e6dc;
            color: #333;
           
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            margin-top:180px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #6f0936;
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        p {
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555;
        }

        .contact-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .contact-form input[type="text"],
        .contact-form input[type="email"],
        .contact-form textarea {
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

        .contact-form textarea {
            resize: vertical;
            height: 150px;
        }

        .contact-form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #6f0936;
            color: #f5eff4;
            border: 1px solid #6f0936;
            border-radius: 0px;
            cursor: pointer;
            font-size: 14px;
            margin-top:30px;
            font-weight: bold;
            font-family: Lato, sans-serif;
            transition: background-color 0.3s, color 0.3s ease, border 0.3s ease;
        }

        .contact-form input[type="submit"]:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }

        .contact-details {
            margin-top: 30px;
            text-align: center;
        }

        .contact-details h2 {
            color: #6f0936;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .contact-details p {
            margin-bottom: 10px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="contact-section">
            <h1>Contact Us</h1>
            <p>If you have any questions, feedback, or need assistance, feel free to reach out to us using the form below or via our contact details.</p>
        </div>

        <div class="contact-form">
            <form action="contact_form.php" method="post">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" required></textarea>
                <input type="submit" value="Send Message">
            </form>
        </div>

        <div class="contact-details">
            <h2>Our Contact Details</h2>
            <p>Email: support@aurabeauty.com</p>
            <p>Phone: (94) 351 1234</p>
            <p>Address: 123 Beauty Avenue, Suite 456, Glamour City, Colombo</p>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
