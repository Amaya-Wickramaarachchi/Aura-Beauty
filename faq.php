<?php
session_start();
require 'connection.php'; 

// Fetch all FAQs from the database
$faqs = [];
$stmt = $conn->query('SELECT * FROM faq ORDER BY id DESC');
while ($row = $stmt->fetch_assoc()) {
    $faqs[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <style>
        body {
            font-family: Lato, sans-serif;
            font-family: 'Lato', sans-serif;
           background-image: url('images/admindb.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat; 
    height: 100vh; 
    margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            margin-top:180px;
            background-color: #faf6f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #6f0936;
            margin-bottom: 20px;
            text-align: center;
        }

        .faq {
            margin-bottom: 20px;
        }

        .faq h2 {
            font-size: 18px;
            color: #6f0936;
            margin-bottom: 10px;
        }

        .faq p {
            font-size: 16px;
            line-height: 1.5;
        }
    </style>
</head>
<?php include 'header.php' ?>
<body>
    
    <div class="container">
    <h1>Frequently Asked Questions</h1>
        <?php if (!empty($faqs)): ?>
            <?php foreach ($faqs as $faq): ?>
                <div class="faq">
                    <h2><?php echo htmlspecialchars($faq['question']); ?></h3>
                    <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No FAQs available at the moment.</p>
        <?php endif; ?>
    </div>
</body>
<?php 
include 'footer.php' ?>
</html>
