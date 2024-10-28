<?php
include 'connection.php';

// Step 2: Fetch product data from the database
$sql = "SELECT id, name, description, price, image_url, category, subcategory FROM products";
$result = $conn->query($sql);
$products = [];

// Organize products by category and subcategory
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[$row['category']][$row['subcategory']][] = $row; 
    }
}

?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Aura Beauty</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    
    <style>
        body {
            margin-top: 100px;
            padding: 0;
            font-family: 'Lato', sans-serif;
            background-color: #fdfbf8;
            background-size: cover;
        }
        .products-container {
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
        .product-card .price {
            font-weight: bold;
            font-size: 18px;
            margin: 10px 0;
            text-align:center;
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
            border: none;
            border-radius: 0; 
            text-decoration: none;
            text-align: center;
            letter-spacing: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .product-card .btn:hover {
            background-color: #f5eff4; 
            color: #6f0936; 
        }
        h1 {
            padding-top:100px;
    color: #6f0936;
    font-size: 40px;
    margin-bottom: 30px;
    padding-left: 20px;
    text-align: center; 
    position: relative;
}

h1::after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background-color: #6f0936; 
    margin: 10px auto 0;
}
        .category-title {
            text-align: center;
            margin: 30px 0;
            font-size: 24px;
            color: #6f0936;
        }
        .subcategory-filter {
            text-align: center;
            margin: 20px 0;
        }
        .subcategory-filter select {
            padding: 10px;
            font-size: 16px;
            border-radius: 0px;
            border: 1px solid #fdfbf8;
            box-sizing: border-box;
            background-color:#fdfbf8;
            border-bottom: 1px solid #6f0936;
        }
    </style>
</head>
<body>
<h1>Our Products</h1>

<?php
// Step 3: Loop through categories and display product cards
$categories = ['skincare', 'makeup'];

foreach ($categories as $category) {
    echo '<h2 class="category-title">' . ucfirst($category) . '</h2>';
    
    // Check if the category is skincare for the subcategory filter
    if ($category === 'skincare') {
        // Get distinct subcategories for the category
        $subcategories = array_keys($products[$category]);
        echo '<div class="subcategory-filter">';
        echo '<label for="subcategory-select">Filter by Category:</label>';
        echo '<select id="subcategory-select" onchange="filterBySubcategory(\'' . $category . '\')">';
        echo '<option value="">-- Select Category --</option>';
        foreach ($subcategories as $subcategory) {
            echo '<option value="' . htmlspecialchars($subcategory) . '">' . htmlspecialchars($subcategory) . '</option>';
        }
        echo '</select>';
        echo '</div>';
    }

    // Display products for the category
    echo '<div class="products-container" id="products-container-' . htmlspecialchars($category) . '">';
    
    if (!empty($products[$category])) {
        foreach ($products[$category] as $subcategory => $items) {
            foreach ($items as $product) {
                echo '<div class="product-card" data-subcategory="' . htmlspecialchars($subcategory) . '">';
                echo '<img src="' . htmlspecialchars($product["image_url"]) . '" alt="' . htmlspecialchars($product["name"]) . '">';
                echo '<h3>' . htmlspecialchars($product["name"]) . '</h3>';
                echo '<div class="price">$' . number_format($product["price"], 2) . '</div>';
                echo '<a href="product.php?id=' . $product["id"] . '" class="btn">View Product</a>';
                echo '</div>';
            }
        }
    } else {
        echo '<p>No products found in this category.</p>';
    }
    
    echo '</div>'; // Close products-container
}
?>

<script>
    function filterBySubcategory(category) {
        var select = document.getElementById('subcategory-select');
        var selectedSubcategory = select.value;
        console.log("Selected subcategory:", selectedSubcategory); 
        var productsContainer = document.getElementById('products-container-' + category);
        var productCards = productsContainer.getElementsByClassName('product-card');

        for (var i = 0; i < productCards.length; i++) {
            var productCard = productCards[i];
            if (selectedSubcategory === "" || productCard.getAttribute('data-subcategory') === selectedSubcategory) {
                productCard.style.display = ""; 
            } else {
                productCard.style.display = "none"; 
            }
        }
    }
</script>

<?php
// Close the database connection
$conn->close();
?>

</body>
<?php include 'footer.php'; ?>
</html>
