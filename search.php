<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <title>Search Products - Aura Beauty</title>
    <style>
        body {
            font-family: Lato, sans-serif;
            margin: 0px;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            color: #6f0936;
            text-align: center;
            margin-bottom: 20px;
            margin-top:150px;
        }

        

        .product-grid {
           
    display: flex;
    justify-content: center; 
    gap: 20px;
    flex-wrap: wrap; 
}

.product-card {
    background-color: #f9f9f9; 
    border: 1px solid #ddd; 
    border-radius: 8px; 
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
    margin: 15px;
    width: 250px; 
    flex-shrink: 0; 
    transition: transform 0.2s; 
}

.product-card:hover {
    transform: translateY(-5px); 
}

.product-card img {
    width: 100%;
    height: 300px;
    border-top-left-radius: 8px; 
    border-top-right-radius: 8px;
}

.product-card h3 {
    color: #333;
    font-size: 18px;
    margin: 15px 0 10px;
    text-align: center; 
}

.product-card p {
    color: #6f0936;
    font-size: 16px;
    margin: 0 0 15px;
    text-align: center;
}

.product-card .btn {
    display: block; 
    width: 100%; 
    padding: 10px 0;
    background-color: #6f0936; 
    color: #fff; 
    text-decoration: none;
    border: none;
    border-radius: 0; 
    text-align: center;
    letter-spacing: 3px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.product-card .btn:hover {
    background-color: #f5eff4; 
    color: #6f0936; 
}
.no-products {
            text-align: center;
            font-size: 20px;
            color: #555;
            margin-top: 150px;
        }
@media (max-width: 768px) {
            .product-card {
                width: 100%;
                margin: 10px;
            }

            input[type="text"], button {
                width: 90%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

    <?php
    // Establish connection to the database
    $servername = "sql104.infinityfree.com";
    $username = "if0_37289253";
    $password = "Piyumi2000";
    $database = "if0_37289253_aura_beauty";

    $conn = new mysqli($servername, $username, $password, $database);

    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if a search query is provided
    if (isset($_GET['q'])) {
        $query = htmlspecialchars($_GET['q']); 

        // SQL query to search for products by name or description
        $sql = "SELECT * FROM products WHERE name LIKE '%$query%' ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Search Results for: " . $query . "</h2>";
            echo '<div class="product-grid">';
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-card">';
                echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '">'; 
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<p class="price">Price: $' . $row['price'] . '</p>';
                // Add a View Details button
                echo '<a href="product.php?id=' . $row['id'] . '" class="btn">View Details</a>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "<p class='no-products'>No products found for: " . $query . "</p>";
        }
    } else {
        echo "<p class='no-products'>Please enter a search term.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>

<?php include 'footer.php'; ?>
</body>
</html>