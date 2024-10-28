<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Aura Beauty - Your go-to source for quality cosmetics and skincare products.">
    <title>Aura Beauty</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 

</head>
<style>
 body {
            margin: 0;
            padding: 0;
            font-family: 'Lato', sans-serif;
            background-color: #f4f4f4;
        }

        .announcement {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #fdf5f1;
            color: #6f0936;
            text-align: center;
            padding: 10px 0;
            z-index: 1001;
            font-size: 14px;
            letter-spacing: 2px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 1px solid #6f0936;
        }

        .announcement a {
            color: #6f0936;
            text-decoration: none;
        }

        .announcement a:hover {
            text-decoration: underline;
        }

        .announcement .close-btn {
            position: absolute !important;
            right: 25px !important;
            background-color: transparent;
            border: none;
            font-size: 20px;
            color: #6f0936;
            cursor: pointer;
        }

        header {
            background-color: #ffffff;
            position: fixed;
            top: 35px;
            left: 0;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 15px 20px !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: top 0.3s, box-shadow 0.3s;
        }

        .logo {
            font-family: 'Montserrat', cursive;
            font-weight: 600;
            font-size: 36px;
            color: #6f0936;
            text-decoration: none;
            margin-right: 40px;
            letter-spacing: 1px;
        }

        nav {
            display: flex;
            align-items: center;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: #6f0936;
            text-decoration: none;
            font-size: 16px;
            line-height: 1.5;
            position: relative;
            padding: 8px 0;
            transition: color 0.3s;
            letter-spacing:1px;
        }

        nav ul li a:hover {
            color: #e46b7d;
            border-bottom: 1px solid #6f0936;
        }

        .icon-container {
            display: flex ;
            align-items: center;
            margin-right: 40px;
        }

        .icon-container a {
            color: #6f0936;
            font-size: 20px;
            margin-left: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }

        .icon-container a:hover {
            color: #e46b7d;
        }

        .search-bar {
        display: flex;
        align-items: center;
        margin-left: 20px;
        position: relative; 
    }

    .search-bar input[type="text"] {
        width: 200px;
        padding: 8px 40px 8px 10px; 
        color: #6f0936;
        outline: none;
        font-size: 14px;
		background-color: #ffffff;
        border: 1px solid #ffffff;
        box-sizing: border-box;
        border-bottom: 1px solid #6f0936;
    }

    .search-bar input[type="text"]:focus {
        width: 300px;
    }

    .search-bar button {
        background-color: transparent;
        border: none;
        color: #6f0936;
        font-size: 20px;
        position: absolute; 
        right: 10px; 
        cursor: pointer;
        padding: 0;
    }

        .toggle-header {
            display: none;
            cursor: pointer;
            font-size: 20px;
            color: #6f0936;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                text-align: center;
            }

            nav ul {
                flex-direction: column;
                display: none;
                margin-bottom: 10px;
            }

            nav.active ul {
                display: flex;
            }

            nav ul li {
                margin-left: 0;
                margin-bottom: 10px;
            }

            .icon-container {
                justify-content: center;
                margin-top: 10px;
            }

            .toggle-header {
                display: block;
                margin-bottom: 10px;
                align:left;
            }

            .search-bar input[type="text"] {
                width: 150px;
            }

            .search-bar input[type="text"]:focus {
                width: 200px;
            }
        }
</style>
<body>

    <div class="announcement">
        FREE STANDARD U.S. SHIPPING W/ $50+. <a href="products.php">Shop Now</a>
        <button class="close-btn">&times;</button>
    </div>

    <header>
    <a href="index.php" class="logo">Aura Beauty</a>
    <div class="toggle-header" onclick="toggleHeader()">&#9776;</div>
    <nav>
        <ul>
            <li><a href="index.php">HOME</a></li>
            <li><a href="products.php">SHOP</a></li>
            <li><a href="about.php">ABOUT US</a></li>
            <li><a href="contact.php">CONTACT</a></li>
            <li><a href="faq.php">FAQs</a></li>
        </ul>
    
</nav>
   
    <div class="icon-container">
    <div class="search-bar">
        <input type="text" id="search-query" placeholder="Search..." onkeydown="handleKeyPress(event)">
        <button onclick="performSearch()"><i class="fas fa-search"></i></button>
    </div>
        <a href="cart.php" class="cart-icon" title="Cart"><i class="fas fa-shopping-cart"></i></a>
        <a href="profile.php" class="profile-icon" title="Profile"><i class="fas fa-user"></i></a>
        <a href="register.php" class="register-icon" title="Register"><i class="fas fa-user-plus"></i></a>
    </div>
    
</header>


    <script>
        document.querySelector('.close-btn').addEventListener('click', function() {
            document.querySelector('.announcement').style.display = 'none';
            document.querySelector('header').style.top = '0';
        });

        function toggleHeader() {
            const nav = document.querySelector('nav');
            nav.classList.toggle('active');
        }

        function handleKeyPress(event) {
    if (event.key === 'Enter') {
        performSearch();
    }
}

function performSearch() {
    const query = document.getElementById('search-query').value.trim();

    if (query) {
        // Redirect to search.php with the search query as a GET parameter
        window.location.href = `search.php?q=${encodeURIComponent(query)}`;
    } else {
        alert('Please enter a product name.');
    }
}

        
    </script>

</body>
</html>
