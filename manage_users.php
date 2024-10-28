<?php
session_start();
include 'connection.php';


// Check if the logged-in user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Fetch users from the database
$sql = "SELECT first_name, last_name, email, birthday, gender, contact_number, address, is_admin FROM user";
$result = $conn->query($sql);

$message = '';
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = $_GET['status'];
    $message = urldecode($_GET['message']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />

    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
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
            background-color: #6f0936;
            color:white;
        }
        h1 {
            text-align: center;
            padding: 20px;
            margin-top: 0;
            background-color: #6F0936;
            color: white;
            border-bottom: 3px solid #d75c8c;
        }
        .action-buttons {
    display: flex;
    justify-content: space-evenly; 
    align-items: center; 
    gap: 10px; 
     cursor: pointer;
}


.action-buttons a {
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 14px; 
    text-decoration: none;
    transition: background-color 0.3s ease;
    color: white; 
     cursor: pointer;
}


.edit-btn {
    background-color: #007BFF; 
    border: none;
     cursor: pointer;
}

.edit-btn:hover {
    background-color: #0056b3;
}


.delete-btn {
    background-color: #dc3545; 
    border: none;
     cursor: pointer;
}

.delete-btn:hover {
    background-color: #c82333; 
}

/* Responsive table styling */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column; 
        gap: 5px; 
    }

    .action-buttons a {
        width: 100%; /* Full-width buttons on mobile */
        text-align: center; /* Center the text in the button */
    }
}
        button {
            width:100%;
            background-color: #6f0936;
            color:white;
            margin-top:10px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
            padding:10px;
             cursor: pointer;
        }

        .message {
            font-weight: bold;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h1>Manage Users</h1>
<!-- Display message -->
<?php if ($message): ?>
        <div class="message <?php echo htmlspecialchars($status); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Birthday</th>
                <th>Gender</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Admin Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['first_name'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['birthday'] . "</td>";
                    echo "<td>" . $row['gender'] . "</td>";
                    echo "<td>" . $row['contact_number'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "<td>" . ($row['is_admin'] ? 'Admin' : 'User') . "</td>";
                    echo "<td>
                            <div class='action-buttons'>
                                <a href='edit_user.php?email=" . $row['email'] . "' class='edit-btn'>Edit</a>
                                <a href='delete_users.php?email=" . $row['email'] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                            </div>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
  
            
            <button type="button" onclick="window.location.href='admin_dashboard.php'" >
    Go to Dashboard
</button>
</body>
</html>

<?php
$conn->close();
?>
