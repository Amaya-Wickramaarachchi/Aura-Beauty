<?php
session_start();
require 'connection.php'; 

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit();
}
// Initialize message variable
$message = '';

// Handle the addition of a new FAQ
if (isset($_POST['add_faq'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare('INSERT INTO faq (question, answer) VALUES (?, ?)');
    $stmt->bind_param('ss', $question, $answer);

    if ($stmt->execute()) {
        $message = "FAQ added successfully.";
    } else {
        $message = "Failed to add FAQ.";
    }
}

// Handle FAQ deletion
if (isset($_GET['delete'])) {
    $faq_id = $_GET['delete'];

    $stmt = $conn->prepare('DELETE FROM faq WHERE id = ?');
    $stmt->bind_param('i', $faq_id);
    if ($stmt->execute()) {
        $message = "FAQ deleted successfully.";
    } else {
        $message = "Failed to delete FAQ.";
    }
}

// Fetch all FAQs
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
    <title>Manage FAQs</title>
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
        .tabs {
            display: flex;
            cursor: pointer;
            margin: 20px 0;
            justify-content: center; 
        }
        .tab {
            padding: 15px 30px;
            background: #f1f1f1;
            border: 1px solid #ccc;
            border-bottom: none;
            transition: background-color 0.3s, transform 0.3s; 
            margin-right: 5px;
            border-radius: 5px 5px 0 0; 
        }
        .tab.active {
            background: white;
            font-weight: bold;
            border-color: #6F0936; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); 
            transform: translateY(-2px); 
        }
        .tab:hover {
            background: #e0e0e0; 
        }
        .tab-content {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 0 0 5px 5px; 
            background: white; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
        }
        .message {
            font-weight: bold;
            text-align: center;
            color: green;
        }
        .error {
            color: red;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f1f1f1;
        }
        input[type="text"],
        input[type="number"],textarea {
            width: 100%;
            padding: 15px;
            color: #6f0936;
            outline: none;
            font-size: 14px;
            background-color:  #f4f4f4;
            border: 1px solid  #f4f4f4;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
        }
        button {
            padding:10px;
            width:100%;
            background-color: #6f0936;
            color:white;
            margin-top:10px;
            margin-bottom: 5px;
            transition: background-color 0.3s;
             cursor: pointer;
        }

        
        button:hover {
            background-color: #d75c8c;
        }
        /* General styling for the action buttons container */
.action-buttons {
    display: flex;
    justify-content: space-evenly; /* Even spacing between buttons */
    align-items: center; /* Vertically align the buttons */
    gap: 10px; /* Space between buttons */
}

/* Styling for both buttons */
.action-buttons a {
    padding: 8px 16px; /* Padding for the buttons */
    border-radius: 5px; /* Rounded corners */
    font-size: 14px; /* Font size */
    text-decoration: none; /* Remove underline */
    transition: background-color 0.3s ease; /* Smooth transition on hover */
    color: white; 
     cursor: pointer;
}

/* Edit button styling */
.edit-btn {
    background-color: #007BFF; /* Blue background for edit */
    border: none;
     cursor: pointer;
}

.edit-btn:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

/* Delete button styling */
.delete-btn {
    background-color: #dc3545; /* Red background for delete */
    border: none;
     cursor: pointer;
}

.delete-btn:hover {
    background-color: #c82333; /* Darker red on hover */
}

/* Responsive table styling */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column; /* Stack buttons vertically on smaller screens */
        gap: 5px; /* Reduce space between buttons */
    }

    .action-buttons a {
        width: 100%; /* Full-width buttons on mobile */
        text-align: center; /* Center the text in the button */
    }
}

        
    </style>
</head>
<body>
    <h1>Manage FAQs</h1>
    
    <!-- Display message -->
    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab active" onclick="showTab('view-faqs')">View FAQs</div>
        <div class="tab" onclick="showTab('add-faq')">Add FAQ</div>
    </div>

    <!-- View FAQs Tab -->
    <div class="tab-content" id="view-faqs">
        <h2>Current FAQs</h2>
        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($faqs as $faq): ?>
        <tr>
            <td><?php echo htmlspecialchars($faq['question']); ?></td>
            <td><?php echo htmlspecialchars($faq['answer']); ?></td>
            <td>
    <div class='action-buttons'>
        <a href='edit_faq.php?id=<?php echo $faq['id']; ?>' class="edit-btn">Edit</a>
        <a href="?delete=<?php echo $faq['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this FAQ?');">Delete</a>
    </div>
</td>

        </tr>
    <?php endforeach; ?>
</tbody>

        </table>
        <button type="button" onclick="window.location.href='faq.php'" >Go to FAQ</button>
        <button type="button" onclick="window.location.href='admin_dashboard.php'" >Go to Dashboard</button>
    </div>

    <!-- Add FAQ Tab -->
    <div class="tab-content" id="add-faq" style="display: none;">
        <h2>Add New FAQ</h2>
        <form method="POST">
            <label for="question">Question:</label>
            <input type="text" id="question" name="question" required><br><br>

            <label for="answer">Answer:</label>
            <textarea id="answer" name="answer" required></textarea><br><br>

            <button type="submit" name="add_faq">Add FAQ</button>
            <button type="button" onclick="window.location.href='faq.php'" >Go to FAQ</button>
            <button type="button" onclick="window.location.href='admin_dashboard.php'" >Go to Dashboard</button>
        </form>
    </div>

    <script>
        function showTab(tab) {
            document.querySelectorAll('.tab-content').forEach((content) => {
                content.style.display = 'none';
            });
            document.querySelectorAll('.tab').forEach((tab) => {
                tab.classList.remove('active');
            });

            document.getElementById(tab).style.display = 'block';
            document.querySelector(`.tab[onclick="showTab('${tab}')"]`).classList.add('active');
        }
    </script>
</body>
</html>
