<?php
session_start();
include 'header.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura Beauty</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lato', sans-serif;
            background-color: #fdfbf8;
            background-size: cover;
            
        }


        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
            text-align: center;
        }

        .text-container {
            flex: 1;
            padding: 20px;
        }

        h1 {
            color: #333;
            letter-spacing: 1px;
        }

        p {
            color: #6f0936;
            letter-spacing:2px;
        }

        .btn {
        margin-top:20px;
        display: inline-block;
        padding: 10px 60px;
        background-color:  #6F0936;
        color: #f5eff4;
        border: 1px solid #6F0936;
        border-radius: 0; 
        text-align: center;
        cursor: pointer;
        text-decoration:none;
        transition: background-color 0.3s ease, color 0.3s ease;
        font-size:10px;
        letter-spacing:3px;
        }

        .btn:hover {
            background-color: #f5eff4;
            color:#6F0936 ;        
        }

        .image-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .image-container img {
            border-radius: 50%;
            width: 500px;
            height: 500px;
            object-fit: cover;
        }

        .slideshow-container {
            z-index: 1;
            position: relative;
            max-width: 100%;
            margin-top: 100px; 
            overflow: hidden;
        }

        .mySlides {
            display: none;
            position: relative;
        }

        img {
            width: 100%;
            height: auto;
        }

        .fade {
            animation: fade 1.5s ease-in-out;
        }

        @keyframes fade {
            from { opacity: .4 }
            to { opacity: 1 }
        }

        .dot {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #6f0936;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active {
            background-color: #f5eff4;
        }

        .slide-btn {
            position:absolute;
            padding: 10px, 60px;
            background-color: #6f0936;
            color: #f5eff4;
            border: 1px solid #6f0936;
            border-radius: 0px;
            cursor: pointer;
            font-size: 15px;
            transition: background-color 0.3s, color 0.3s ease, border 0.3s ease;
        }
        

        .slide-btn:hover {
            background-color: #f5eff4;
            color: #6f0936;
            border: 1px solid #6f0936;
        }

        .slide1 .slide-btn {
            bottom: 200px;
            left: 140px;
        }

        .slide2 .slide-btn {
            bottom: 80px;
            right: 180px;
        }

        .slide3 .slide-btn {
            bottom: 50px;
            right: 200px;
            
        }
        .slide1-text {
    position: absolute;
    top: 40%;
    left: 40px; 
    transform: translateY(-50%);
    color: black;
    font-size:30px;
    padding: 20px;
    border-radius: 8px;
    max-width: 350px; 
    text-align:center;
}
       

        .slide2-text {
            font-family: Lato, sans-serif;
            position: absolute;
            bottom: 190px;
            left: 75%;
            text-align: right;
            transform: translateX(-50%);
            color: black;
            border-radius: 15px;
            font-size: 30px;
            text-align: center;
        }

        .slide3-text {
            font-family: Lato, sans-serif;
            position: absolute;
            top:10%;
            left: 75%;
            text-align: right;
            transform: translateX(-50%);
            color: black;
            border-radius: 15px;
            font-size: 30px;
            text-align: center;
        }

        .latest-products-section {
         
    padding: 50px 40px;
    background-color: #ffffff;
    text-align: left;
    
}

.latest-products-section h2 {
    color: #6f0936;
    font-size: 40px;
    margin-bottom: 30px;
    padding-left: 20px;
    text-align: center; 
    position: relative;
}

.latest-products-section h2::after {
    content: '';
    display: block;
    width: 60px;
    height: 4px;
    background-color: #6f0936; 
    margin: 10px auto 0;
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
    height: auto;
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

   
@media (max-width: 1200px) {
    .main-content {
        flex-direction: column;
        padding: 30px;
    }
    .image-container img {
        width: 350px;
        height: 350px;
    }
    h1 {
        font-size: 32px;
    }
    
}


@media (max-width: 768px) {
    .slideshow-container {
        margin-top: 280px;
    }
    .slide-btn {
        font-size: 12px;
        padding: 8px 40px;
    }
    .slide1 .slide-btn {
        bottom: 120px;
        left: 80px;
    }

    .slide2 .slide-btn {
        bottom: 20px;
        right: 20px;
    }

    .slide3 .slide-btn {
        bottom: 10px;
        right: 20px;
    }

    .slide1-text, .slide2-text, .slide3-text {
        font-size: 20px;
        max-width: 300px;
    }
    
    .slide2-text, .slide3-text{
            position: absolute;
            bottom: 65px;
            text-align: center;
        }
        
    h1 {
        font-size: 25px;
    }
    .product-card {
        width: 45%;
    }
    .products-container {
        justify-content: center;
        flex-wrap: wrap;
    }
    .text-container {
        padding: 10px;
    }
    .image-container img {
        width: 300px;
        height: 300px;
    }
    .latest-products-section {
        padding: 30px 10px;
    }
    .product-card {
        width: 100%; 
    }
}


@media (max-width: 480px) {
    body {
        padding-top: 100px;
    }
    h1 {
        font-size: 22px;
    }
    .btn {
        padding: 8px 40px;
        font-size: 8px;
    }
    .products-container {
        flex-wrap: wrap;
        justify-content: center;
    }
    .product-card {
        width: 90%;
    }
    .slideshow-container img {
        height: 300px;
    }
    .slide1 .slide-btn {
        bottom: 80px;
        left: 40px;
        font-size: 12px;
        padding: 8px 30px;
    }

    .slide2 .slide-btn {
        bottom: 20px;
        right: 40px;
        font-size: 12px;
        padding: 8px 30px;
    }

    .slide3 .slide-btn {
        bottom: 10px;
        right: 60px;
        font-size: 12px;
        padding: 8px 30px;
    }

    .slide1-text, .slide2-text, .slide3-text {
        font-size: 16px;
        max-width: 200px;
        left: 20px; 
    }

    .slide2-text, .slide3-text {
        left: 60%;
        font-size: 18px;
    }
    .image-container img {
        width: 200px;
        height: 200px;
    }
}

    </style>
</head>
<body>
    

    <!-- Slideshow -->
    <div class="slideshow-container">
        <div class="mySlides fade slide1">
            <img src="images/slide1.jpg" style="width:100%">
            <div class="slide1-text">
               <h1> Elevate <br>with<br>Aura Beauty</h1><br><i>"Elevate your beauty routine—shop now and discover the perfect products that celebrate your unique glow."</i>
            </div>
            <a href="products.php" class="btn slide-btn">Shop Now</a>
        </div>

        <div class="mySlides fade slide2">
            <img src="images/slide2.jpg" style="width:100%">
            <div class="slide2-text">
               <h1> Discover Your Perfect Skin Care Match!</h1><i>"Unlock radiant beauty with our personalized skin type finder.<br> Find the best products tailored just for you!"</i>
</div>
               <a href="skin_type.php" class="btn slide-btn">Identify Your Skin Type</a>
            </div>

        <div class="mySlides fade slide3">
            <img src="images/slide3.png" style="width:100%">
            <div class="slide3-text">
               <h1>Discover Aura Beauty</h1><i>" Discover why Aura Beauty is your perfect partner in achieving radiant, healthy skin."</i>
            </div>
            <a href="about.php" class="btn slide-btn">Learn More About Us</a>
        </div>

        <!-- Navigation dots -->
        <div style="text-align:center">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <!-- Latest Products Section -->
    <div class="latest-products-section">
    <h2>Latest Products</h2>
    <div class="products-container">
        <div class="product-card">
            <img src="images/product1.png" alt="Mystique Glam Palette">
            <h3>Mystique Glam Palette</h3>
            <p>$29.99</p>
            <a href="product.php?id=12" class="btn">ADD TO CART</a>

        </div>
        <div class="product-card">
            <img src="images/product2.png" alt="Flawless Finish Compact">
            <h3>Flawless Finish Compact</h3>
            <p>$39.99</p>
            <a href="product.php?id=13" class="btn">ADD TO CART</a>

        </div>
        <div class="product-card">
            <img src="images/product3.png" alt="Velvet Charm Lipstick">
            <h3>Velvet Charm Lipstick</h3>
            <p>$44.99</p>
            <a href="product.php?id=14" class="btn">ADD TO CART</a>

        </div>
        <div class="product-card">
            <img src="images/product5.jpg" alt="Sculpt & Define Contour Stick">
            <h3>Sculpt & Define Contour Stick</h3>
            <p>$24.99</p>
            <a href="product.php?id=15" class="btn">ADD TO CART</a>

        </div>
    </div>
</div>

    <div class="main-content">
        <div class="text-container">
            <h1>Welcome to Aura Beauty</h1>
            <p>Welcome to Aura Beauty, where your natural radiance meets personalized skincare. At Aura Beauty, we believe that true beauty comes from embracing and enhancing your unique features. Our carefully curated products are designed to nourish, protect, and celebrate your skin, helping you achieve a healthy, glowing complexion.</p><p> With a commitment to quality and a passion for individuality, we offer tailored skincare solutions that address your specific needs. Discover the perfect products for your skin type and embark on a journey to luminous beauty with Aura Beauty.</p>
            
            <a href="skin_type.php" class="btn">IDENTIFY YOUR SKIN TYPE</a>
        </div>
        <div class="image-container">
            <img src="images/circle.png">
        </div>
    </div>

    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) { slideIndex = 1 }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 5000);
        }
    </script>

    <!-- Include Footer -->
    <?php include 'footer.php'; ?>
</body>
</html>