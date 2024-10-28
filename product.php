<?php
session_start();
$is_logged_in = isset($_SESSION['user_email']);

// Database connection setup
$host = 'sql104.infinityfree.com';
$db = 'if0_37289253_aura_beauty';
$user = 'if0_37289253';
$pass = 'Piyumi2000';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Display message if present in session
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

if ($product_id) {
    // Fetch product data
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Invalid product ID!";
    exit;
}

$product_name = htmlspecialchars($product['name']);
$product_price = htmlspecialchars($product['price']);
$product_image = htmlspecialchars($product['image_url']);

// Handle review form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_text'], $_POST['rating'])) {
    if ($is_logged_in) {
        $review_text = htmlspecialchars($_POST['review_text']);
        $rating = intval($_POST['rating']);
        $user_email = $_SESSION['user_email'];
        
        // Insert the review into the database
        $stmt = $pdo->prepare('INSERT INTO reviews (product_id, user_email, review_text, rating) VALUES (?, ?, ?, ?)');
        $stmt->execute([$product_id, $user_email, $review_text, $rating]);

        $_SESSION['message'] = 'Review submitted successfully!';
        header("Location: product.php?id=$product_id");
        exit;
    } else {
        $_SESSION['message'] = 'You must be logged in to submit a review!';
        header("Location: product.php?id=$product_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product_name; ?></title>
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #6f0936;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            
            text-align: center;
            margin-top: 140px;
            font-size: 2.5em;
            color: #6f0936;
        }
        .product-detail {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            letter-spacing: 1.5px;
        }
        .product-image {
            flex: 1;
            padding-right: 20px;
        }
        .product-image img {
            width: 100%;
            border-radius: 0px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-details {
            flex: 1.5;
        }
        .product-details h2 {
            color: #6f0936;
            font-size: 1.8em;
            margin-bottom: 15px;
        }
        .quantity-container {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .quantity-container label {
            font-size: 16px;
            color: #333;
            margin-right: 10px;
        }
        #quantity {
            width: 100%;
            max-width: 100px;
            background: #ffffff;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            color: #333;
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="%236f0936" d="M1.5 5l7 7 7-7H1.5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
        }
        #quantity:hover,
        #quantity:focus {
            border-color: #6f0936;
            box-shadow: 0 0 5px rgba(111, 9, 54, 0.5);
        }
        #quantity::-ms-expand {
            display: none;
        }
        .add-to-cart {
            width: 98%;
            margin-top: 20px;
            padding: 10px;
            background-color: #6f0936;
            color: #fff;
            border: 1px solid #6f0936;
            border-radius: 0px;
            letter-spacing:1px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bolder;
            transition: background-color 0.3s, color 0.3s;
            margin-bottom:10px;
        }
        .add-to-cart:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }
        .customer-reviews {
            margin-top: 30px;
            background: #ffffff;
            padding: 20px;
            border-radius: 0px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .customer-reviews h3 {
            color: #6f0936;
            margin-bottom: 15px;
        }
        .reviews-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px 0;
            list-style: none;
            padding: 0;
        }
        .review-card {
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 0px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1 1 300px;
            max-width: 300px;
            transition: transform 0.2s;
        }
        .review-card:hover {
            transform: scale(1.05);
        }
        .review-content {
            text-align: left;
        }
        .rating {
            margin-bottom: 10px;
        }
        .star {
            color: #ccc;
            font-size: 20px;
        }
        .star.filled {
            color: #ffcc00;
        }
        .star-rating {
            display: flex;
            gap: 5px;
            font-size: 30px;
            cursor: pointer;
        }
        .star.selected,
         {
            color: #f39c12;
        }
        .review-card strong {
            display: block;
            margin-top: 5px;
        }
        .review-form {
            margin-top: 20px;
        }
        .rating-container {
            margin-top: 10px;
            display: flex;
            align-items: center;
        }
        .rating-label {
            margin-right: 20px;
            width: 50px;
        }
        .review-text-container {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        .review-text-container label {
            margin-right: 10px;
        }
        .review-text-container textarea {
            width: 100%;
            padding: 5px;
            color: #6f0936;
            outline: none;
            font-size: 14px;
            background-color: #ffffff;
            border: 1px solid #ffffff;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
        }
        .review-text-container textarea:focus {
            border-color: #6f0936;
            box-shadow: 0 0 5px rgba(111, 9, 54, 0.5);
            outline: none;
        }
       /* Mobile Styles */
@media (max-width: 600px) {
    body {
        padding: 10px;
    }
    h1 {
        font-size: 2em;
    }
    .product-detail {
        flex-direction: column;
        padding: 10px;
    }
    .product-image {
        padding-right: 0;
    }
    .product-details {
        margin-top: 20px;
    }
    .add-to-cart {
        width: 100%;
    }
    .customer-reviews h3 {
        font-size: 1.5em;
    }
    .review-text-container textarea {
        font-size: 12px;
    }
}

/* Tablet Styles */
@media (min-width: 601px) and (max-width: 1024px) {
    h1 {
        font-size: 2.2em;
    }
    .product-detail {
        flex-direction: row;
    }
    .add-to-cart {
        width: auto;
    }
}

/* Desktop Styles */
@media (min-width: 1025px) {
    h1 {
        font-size: 2.5em;
    }
    .product-detail {
        max-width: 1200px;
        margin: 0 auto;
    }
}

    </style>
</head>
<body>

<?php include 'header.php'; ?>

<h1><?php echo $product_name; ?></h1>

<div class="product-detail">
    <div class="product-image">
        <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>">
    </div>
    <div class="product-details">
        <h2>Price: $<?php echo $product_price; ?></h2>
        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        <h3>Ingredients:</h3><p> <?php echo nl2br(htmlspecialchars($product['ingredients'])); ?></p>
        <form action="add_to_cart.php" method="POST">
            <div class="quantity-container">
                <label for="quantity"><h3>Quantity:</h3></label>
                <select id="quantity" name="quantity">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <button type="submit" class="add-to-cart">ADD TO CART</button>
        </form>

        <?php if ($message): ?>
            <p style="color:green;"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Customer Reviews Section -->
<div class="customer-reviews">
   
    
    <!-- Review Form -->
    <?php if ($is_logged_in): ?>
        <div class="review-form">
            <h3>Leave a Review</h3>
            <form method="POST">
                <div class="star-rating">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                </div>
                <input type="hidden" name="rating" id="rating" value="0">
                <div class="review-text-container">
                    <label for="review_text">Your Review:</label>
                    <textarea name="review_text" id="review_text" rows="3" required></textarea>
                </div>

                <button type="submit" class="add-to-cart">SUBMIT REVIEW</button>
            </form>
        </div>
    <?php else: ?>
        <p>You must be logged in to leave a review!</p>
    <?php endif; ?>
    <h3>Customer Reviews</h3>
    <ul class="reviews-container">
        
        <?php
        $stmt = $pdo->prepare('SELECT * FROM reviews WHERE product_id = ?');
        $stmt->execute([$product_id]);
        $reviews = $stmt->fetchAll();

        if ($reviews) {
            foreach ($reviews as $review) {
                $review_text = htmlspecialchars($review['review_text']);
                $rating = intval($review['rating']);
                echo '<li class="review-card">';
                echo '<div class="review-content">';
                echo '<div class="rating">';
                for ($i = 1; $i <= 5; $i++) {
                    echo '<span class="star' . ($i <= $rating ? ' filled' : '') . '">★</span>';
                }
                echo '</div>';
                echo '<strong>' . $review_text . '</strong>';
                echo '</div>';
                echo '</li>';
            }
        } else {
            echo '<p>No reviews yet. Be the first to leave a review!</p>';
        }
        ?>
    </ul>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        let selectedRating = 0;

        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                highlightStars(star.dataset.value);
            });

            star.addEventListener('mouseout', () => {
                highlightStars(selectedRating);
            });

            star.addEventListener('click', () => {
                selectedRating = star.dataset.value;
                ratingInput.value = selectedRating; // Store selected rating
            });
        });

        function highlightStars(rating) {
            stars.forEach(star => {
                star.classList.toggle('filled', star.dataset.value <= rating);
            });
        }
    });
</script>
<?php include 'footer.php'; ?>
</body>
</html>
