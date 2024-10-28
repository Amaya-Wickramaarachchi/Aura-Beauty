<?php
session_start();
require 'connection.php'; // Include the database connection file

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit();
}

$message = '';

// Handle the update of an FAQ
if (isset($_POST['update_faq'])) {
    $faq_id = $_POST['id'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare('UPDATE faq SET question = ?, answer = ? WHERE id = ?');
    $stmt->bind_param('ssi', $question, $answer, $faq_id);

    if ($stmt->execute()) {
        header('Location: manage_faq.php?status=success&message=' . urlencode("FAQ updated successfully."));
        exit();
    } else {
        $message = "Failed to update FAQ.";
    }
}



// Fetch the FAQ to be edited
if (isset($_GET['id'])) {
    $faq_id = $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM faq WHERE id = ?');
    $stmt->bind_param('i', $faq_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $faq = $result->fetch_assoc();

    if (!$faq) {
        // FAQ not found
        header('Location: manage_faq.php');
        exit();
    }
} else {
    header('Location: manage_faq.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit FAQ</title>
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
        textarea {
            width: 100%;
            padding: 15px;
            color: #6f0936;
            outline: none;
            font-size: 14px;
            background-color: #f4f4f4;
            border: 1px solid #f4f4f4;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
        }
        button {
            padding: 10px;
            width: 100%;
            background-color: #6f0936;
            color: white;
            margin-top: 10px;
            margin-bottom: 5px;
            transition: background-color 0.3s;
        }
    </style>
</head>
<body>
    <h1>Edit FAQ</h1>

    <!-- Display message -->
    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $faq['id']; ?>">
        <label for="question">Question:</label>
        <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($faq['question']); ?>" required>

        <label for="answer">Answer:</label>
        <textarea id="answer" name="answer" required><?php echo htmlspecialchars($faq['answer']); ?></textarea>

        <button type="submit" name="update_faq">Update FAQ</button>
        <button type="button" onclick="window.location.href='manage_faq.php'">Cancel</button>
    </form>
</body>
</html>
