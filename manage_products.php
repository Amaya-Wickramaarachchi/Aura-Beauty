<?php
session_start();

// Redirect to login if the user is not an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit();
}

include 'connection.php';

// Enable error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$image_directory = 'images/products/';

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Common fields
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $ingredients = $_POST['ingredients'];
    $category = $_POST['category'];
    $subcategory = $_POST['subcategory'];

    // Prepare SQL statements based on action
    if ($action === 'add') {
        // Handle Add Product
        $image = $_FILES['image']['name'];
        $target = $image_directory . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, ingredients, category, subcategory, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssss", $name, $description, $price, $ingredients, $category, $subcategory, $target);

            if ($stmt->execute()) {
                $message = "Product added successfully!";
            } else {
                $message = "Error adding product: " . $stmt->error;
            }
        } else {
            $message = "Failed to upload image.";
        }

    } elseif ($action === 'update') {
        // Handle Update Product
        $id = $_POST['id'];
        $image = $_FILES['image']['name'];

        if ($image) {
            $target = $image_directory . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        } else {
            $target = $_POST['current_image'];
        }

        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, ingredients = ?, category = ?, subcategory = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("ssdssssi", $name, $description, $price, $ingredients, $category, $subcategory, $target, $id);

        if ($stmt->execute()) {
            $message = "Product updated successfully!";
        } else {
            $message = "Error updating product: " . $stmt->error;
        }

    } elseif ($action === 'delete') {
        // Handle Delete Product
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $message = "Product deleted successfully!";
        } else {
            $message = "Error deleting product: " . $stmt->error;
        }
    }
}

// Fetch all products from the database
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            text-align: center;
            padding: 20px;
            margin-top: 0;
            background-color: #6f0936;
            color: white;
            border-bottom: 3px solid #d75c8c;
        }
        .container {
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        button {
            width: 100%;
            background-color: #6f0936;
            color: white;
            margin-bottom: 10px;
            transition: background-color 0.3s;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }
        button:hover {
            background-color: #d75c8c;
        }
        .image-preview {
            display: none;
            width: 100px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-body {
            padding: 20px;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 15px;
            color: #6f0936;
            outline: none;
            font-size: 14px;
            background-color:  white;
            border: 1px solid  white;
            box-sizing: border-box;
            border-bottom: 1px solid #6f0936;
            margin-bottom: 15px;
        }
        
        .nav-tabs{
            display: flex;
            cursor: pointer;
            margin: 20px 0;
            justify-content: center;
        }
        .nav-tabs .nav-link {
            
            padding: 15px 30px;
            background: #f1f1f1;
            border: 1px solid #ccc;
            border-bottom: none;
            transition: background-color 0.3s, transform 0.3s; 
            margin-right: 5px; 
            border-radius: 5px 5px 0 0; 
        }
        .nav-tabs .nav-link.active {
            background: white;
            font-weight: bold;
            border-color: #6F0936;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); 
            transform: translateY(-2px); /* Lift the active tab slightly */
        }
        
        .alert {
            border-radius: 5px;
        }
        .table {
            margin-top: 20px;
        }
        .table th {
            background-color: #6f0936;
            color: white;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <h1>Manage Products</h1>
    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#addProduct">Add Product</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#viewProducts">View Products</a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content">
            <!-- Add Product Tab -->
            <div id="addProduct" class="tab-pane fade show active">
                <h2>Add New Product</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="name">Product Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" step="0.01" id="price" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="ingredients">Ingredients:</label>
                        <textarea id="ingredients" name="ingredients" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="skincare">Skincare</option>
                            <option value="makeup">Makeup</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subcategory">Subcategory:</label>
                        <input type="text" id="subcategory" name="subcategory" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Image:</label>
                        <input type="file" id="image" name="image" class="form-control" required onchange="previewImage(this);">
                        <img id="imagePreview" class="image-preview" alt="Image Preview">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <button type="button" onclick="window.location.href='admin_dashboard.php'" class="btn btn-secondary">Go to Dashboard</button>
                </form>
            </div>

            <!-- View Products Tab -->
            <div id="viewProducts" class="tab-pane fade">
                <h2>Product List</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Ingredients</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['description']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td><?php echo htmlspecialchars($product['ingredients']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" width="100" style="border-radius: 5px;"></td>
                            <td>
                                <button class="btn btn-primary" onclick="editProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', '<?php echo htmlspecialchars($product['description']); ?>', <?php echo htmlspecialchars($product['price']); ?>, '<?php echo htmlspecialchars($product['ingredients']); ?>', '<?php echo htmlspecialchars($product['category']); ?>', '<?php echo htmlspecialchars($product['subcategory']); ?>', '<?php echo htmlspecialchars($product['image_url']); ?>')">Update</button>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editProductForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" id="productId" name="id" value="">
                        <input type="hidden" id="currentImage" name="current_image" value="">
                        <div class="form-group">
                            <label for="editName">Product Name:</label>
                            <input type="text" id="editName" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Description:</label>
                            <textarea id="editDescription" name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editPrice">Price:</label>
                            <input type="number" step="0.01" id="editPrice" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editIngredients">Ingredients:</label>
                            <textarea id="editIngredients" name="ingredients" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editCategory">Category:</label>
                            <select id="editCategory" name="category" class="form-control" required>
                                <option value="skincare">Skincare</option>
                                <option value="makeup">Makeup</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editSubcategory">Subcategory:</label>
                            <input type="text" id="editSubcategory" name="subcategory" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editImage">Upload New Image (optional):</label>
                            <input type="file" id="editImage" name="image" class="form-control" onchange="previewImage(this);">
                            <img id="editImagePreview" class="image-preview" alt="Image Preview">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (input.id === 'image') {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                    } else {
                        document.getElementById('editImagePreview').src = e.target.result;
                        document.getElementById('editImagePreview').style.display = 'block';
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function editProduct(id, name, description, price, ingredients, category, subcategory, image) {
            document.getElementById('productId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editPrice').value = price;
            document.getElementById('editIngredients').value = ingredients;
            document.getElementById('editCategory').value = category;
            document.getElementById('editSubcategory').value = subcategory;
            document.getElementById('currentImage').value = image;
            document.getElementById('editImagePreview').src = image; // Set current image for preview
            document.getElementById('editImagePreview').style.display = 'block'; // Show the current image preview
            $('#editProductModal').modal('show'); // Show the modal
        }
    </script>
</body>
</html>
