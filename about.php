<?php
session_start();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Aura Beauty</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/about.css">

</head>
<style>
.shops-section {
            text-align: center;
            padding: 20px;
        }

        
        .locations {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 20px; 
            margin-top: 20px;
        }

        
        .location {
            flex: 1;
            max-width: 200px; 
            text-align: center;
        }

        
        .location img {
            width: 100%;
            height: auto;
            border-radius: 180px; 
        }

        
        .location h4 {
            margin-top: 10px;
            font-size: 16px;
        }
        </style>
<body>

    <div class="container">
        
        <div class="about-section">
            <div class="about-content">
                <h1>About Us</h1>
                <p>At Aura Beauty, we believe that every individual deserves to feel confident and radiant in their own skin. Our mission is to provide personalized skincare solutions tailored to each customer’s unique skin type and needs. Understanding that skincare is not one-size-fits-all, we are committed to offering a diverse range of products that cater to various concerns, from hydration to anti-aging and everything in between.</p>
                <p>Our expert team is dedicated to educating customers on the importance of understanding their skin and selecting the right products for their specific requirements. We prioritize quality and efficacy, using only the finest ingredients in our formulations to ensure that each product not only feels luxurious but also delivers real results.</p>
                <p>Join us on a journey to discover your skin’s true potential, and let Aura Beauty empower you to embrace your natural beauty with confidence and grace. Together, we can create a skincare routine that highlights your uniqueness and enhances your inner glow.</p>
            </div>
            <div class="about-image">
                <img src="images/about.jpg" alt="About Aura Beauty">
            </div>
        </div>

        <div class="text-section">
            <h2>Our Story</h2>
            <p>Founded in 2024, Aura Beauty emerged from a passion for beauty and a commitment to quality. Our founders recognized the need for a personalized approach to skincare that celebrates individuality and promotes self-love.</p>
        </div>

       <div class="shops-section">
    <h2>Our Shops</h2>
    <p>Discover our stores located across the country, providing premium products and exceptional service.</p>

    <div class="locations">
        <!-- Location 1 -->
        <div class="location">
            <img src="images/locations/1.png" alt="Location 1">
            <h4>New York</h4>
        </div>

        <!-- Location 2 -->
        <div class="location">
            <img src="images/locations/2.png" alt="Location 2">
            <h4>Los Angeles</h4>
        </div>

        <!-- Location 3 -->
        <div class="location">
            <img src="images/locations/3.png" alt="Location 3">
            <h4>Chicago</h4>
        </div>

        <!-- Location 4 -->
        <div class="location">
            <img src="images/locations/4.png" alt="Location 4">
            <h4>Miami</h4>
        </div>
    </div>
</div>

        <div class="features-container">
            <h2>Our Values</h2>
            <div class="features-section">
                <div class="icon-item">
                    <i class="fas fa-leaf"></i>
                    <p>Ethical Sourcing</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-paw"></i>
                    <p>Cruelty-Free</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-balance-scale"></i>
                    <p>Balanced Beauty</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-recycle"></i>
                    <p>Sustainability</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-heart"></i>
                    <p>Customer Love</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-user-friends"></i>
                    <p>Community Engagement</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-thumbs-up"></i>
                    <p>Quality Assurance</p>
                </div>
            </div>
        </div>

        <h2>Why Choose Us?</h2>
        <div class="container">
            <p>We offer a unique, personalized skincare experience designed to meet your specific needs. Our advanced skin type finder and customized recommendations ensure that you achieve glowing, healthy skin.</p>
        </div>

        <h2>Meet Our Team</h2>
        <div class="team-section">
            <div class="team-member">
                <img src="images/staff/s2.png" alt="Jane Doe">
                <h3>Piyumi Amaya</h3>
                <p>Founder & CEO</p>
                <p>Amaya is passionate about creating high-quality beauty products that empower individuals to feel confident in their skin.</p>
            </div>
            <div class="team-member">
                <img src="images/staff/s3.png" alt="John Smith">
                <h3>Thirath Nirodha</h3>
                <p>Head of Product Development</p>
                <p>Thirath oversees the development of our innovative skincare line, ensuring every product meets our high standards.</p>
            </div>
            <div class="team-member">
                <img src="images/staff/s1.png" alt="Emily White">
                <h3>Emily White</h3>
                <p>Marketing Manager</p>
                <p>Emily is dedicated to spreading the word about Aura Beauty and connecting with our customers.</p>
            </div>
        </div>
    </div>
    
</body>
</html>

<?php
include 'footer.php';
?>
<?php
session_start();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Aura Beauty</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/about.css">

</head>
<style>
.shops-section {
            text-align: center;
            padding: 20px;
        }

        
        .locations {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 20px; 
            margin-top: 20px;
        }

        
        .location {
            flex: 1;
            max-width: 200px; 
            text-align: center;
        }

        
        .location img {
            width: 100%;
            height: auto;
            border-radius: 180px; 
        }

        
        .location h4 {
            margin-top: 10px;
            font-size: 16px;
        }
        </style>
<body>

    <div class="container">
        
        <div class="about-section">
            <div class="about-content">
                <h1>About Us</h1>
                <p>At Aura Beauty, we believe that every individual deserves to feel confident and radiant in their own skin. Our mission is to provide personalized skincare solutions tailored to each customer’s unique skin type and needs. Understanding that skincare is not one-size-fits-all, we are committed to offering a diverse range of products that cater to various concerns, from hydration to anti-aging and everything in between.</p>
                <p>Our expert team is dedicated to educating customers on the importance of understanding their skin and selecting the right products for their specific requirements. We prioritize quality and efficacy, using only the finest ingredients in our formulations to ensure that each product not only feels luxurious but also delivers real results.</p>
                <p>Join us on a journey to discover your skin’s true potential, and let Aura Beauty empower you to embrace your natural beauty with confidence and grace. Together, we can create a skincare routine that highlights your uniqueness and enhances your inner glow.</p>
            </div>
            <div class="about-image">
                <img src="images/about.jpg" alt="About Aura Beauty">
            </div>
        </div>

        <div class="text-section">
            <h2>Our Story</h2>
            <p>Founded in 2024, Aura Beauty emerged from a passion for beauty and a commitment to quality. Our founders recognized the need for a personalized approach to skincare that celebrates individuality and promotes self-love.</p>
        </div>

       <div class="shops-section">
    <h2>Our Shops</h2>
    <p>Discover our stores located across the country, providing premium products and exceptional service.</p>

    <div class="locations">
        <!-- Location 1 -->
        <div class="location">
            <img src="images/locations/1.png" alt="Location 1">
            <h4>New York</h4>
        </div>

        <!-- Location 2 -->
        <div class="location">
            <img src="images/locations/2.png" alt="Location 2">
            <h4>Los Angeles</h4>
        </div>

        <!-- Location 3 -->
        <div class="location">
            <img src="images/locations/3.png" alt="Location 3">
            <h4>Chicago</h4>
        </div>

        <!-- Location 4 -->
        <div class="location">
            <img src="images/locations/4.png" alt="Location 4">
            <h4>Miami</h4>
        </div>
    </div>
</div>

        <div class="features-container">
            <h2>Our Values</h2>
            <div class="features-section">
                <div class="icon-item">
                    <i class="fas fa-leaf"></i>
                    <p>Ethical Sourcing</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-paw"></i>
                    <p>Cruelty-Free</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-balance-scale"></i>
                    <p>Balanced Beauty</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-recycle"></i>
                    <p>Sustainability</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-heart"></i>
                    <p>Customer Love</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-user-friends"></i>
                    <p>Community Engagement</p>
                </div>
                <div class="icon-item">
                    <i class="fas fa-thumbs-up"></i>
                    <p>Quality Assurance</p>
                </div>
            </div>
        </div>

        <h2>Why Choose Us?</h2>
        <div class="container">
            <p>We offer a unique, personalized skincare experience designed to meet your specific needs. Our advanced skin type finder and customized recommendations ensure that you achieve glowing, healthy skin.</p>
        </div>

        <h2>Meet Our Team</h2>
        <div class="team-section">
            <div class="team-member">
                <img src="images/staff/s2.png" alt="Jane Doe">
                <h3>Piyumi Amaya</h3>
                <p>Founder & CEO</p>
                <p>Amaya is passionate about creating high-quality beauty products that empower individuals to feel confident in their skin.</p>
            </div>
            <div class="team-member">
                <img src="images/staff/s3.png" alt="John Smith">
                <h3>Thirath Nirodha</h3>
                <p>Head of Product Development</p>
                <p>Thirath oversees the development of our innovative skincare line, ensuring every product meets our high standards.</p>
            </div>
            <div class="team-member">
                <img src="images/staff/s1.png" alt="Emily White">
                <h3>Emily White</h3>
                <p>Marketing Manager</p>
                <p>Emily is dedicated to spreading the word about Aura Beauty and connecting with our customers.</p>
            </div>
        </div>
    </div>
    
</body>
</html>

<?php
include 'footer.php';
?>
