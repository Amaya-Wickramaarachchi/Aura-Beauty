<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skin Type Identifier</title>
    <style>
            @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap');

        body {
            font-family: 'Lato', sans-serif;
            background-color: #f0f0f0;
             background-image: url("images/skinbg.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            margin-top:10px;
        }
      
        
        .container {
    display: flex;
    border-radius: 50px 20px 50px 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    flex-direction: column;
    margin-top:150px;
    justify-content: center; 
    align-items: center;
    height: 450px; 
    padding: 30px; 
    width: 620px;
    max-width: 80%; 
    background-color: #f5eff4;
    margin-bottom:20px;
   
}
        .text-area {
            flex: 1;
            margin-right: 20px;
            width:300px; 
        }

        h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #6f0936;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .hidden {
            display: none;
        }

        .btn {
            display: block;
            padding: 12px 60px;
            background-color: white;
            color: #6F0936;
            border: 1px solid #6F0936;
            border-radius: 0; 
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-size: 15px;
            letter-spacing: 3px;
            margin: 20px 0; 
        }

        .btn:hover {
            background-color: #6f0936;
            color: #f5eff4;     
        }
        padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    max-width: 800px;
}


#result {
    display: flex;
    justify-content: center; 
    align-items: center; 
        flex-direction: column;
    text-align: center;
    padding: 20px; 
    background-color: #f9f9f9; 
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.result-details {
    max-width: 600px; 
}

/* Product Cards Container */
.product-card {
    cursor: pointer;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.product-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Add some spacing */
#product-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}


        .image-container {
            flex: 0 0 150px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        img {
            width: 300px; 
            border-radius: 50%;
            margin-right:10px;
            height:400px;
        }
    </style>
</head>
<body>
    
<div class="container">
<h2>Skin Type Analyzer</h2>

    <div id="quiz">
        <div id="question-container" style="display: flex; align-items: center;">
            <div class="text-area">
                
                <p id="question"></p>
                <div id="answers"></div>
            </div>
            <div class="image-container">
                <img id="question-image" src="" alt="Question Image">
            </div>
        </div>
    </div>
    <div id="result" class="hidden">
        <div class="result-details">
            <h2>Your Skin Type: <span id="skin-type"></span></h2>
            <h3>Recommended Products:</h3>
            <div id="product-cards"></div>
            <a href="products.php" class="btn" id="shop-now-button">Shop Now</a>
            <div class="btn" id="restart-button">Take Quiz Again</div>
        </div>
    </div>
</div>

    <script>
        const questions = [
    {
        question: "How does your skin feel throughout the day? 🌞",
        answers: [
            { text: "It tends to feel dry or flaky", skinType: "Dry" },
            { text: "It feels pretty balanced", skinType: "Normal" },
            { text: "I sometimes notice oiliness by midday", skinType: "Combination" },
        ],
        image: "images/q1.jpg"
    },
    {
        question: "Do you find your skin gets a bit shiny or oily as the day goes on? 💧",
        answers: [
            { text: "Yes, I notice that", skinType: "Oily" },
            { text: "Not really, it feels okay", skinType: "Normal" },
        ],
        image: "images/q2.png"
    },
    {
        question: "Have you experienced any redness or irritation? ❤️",
        answers: [
            { text: "Yes, sometimes", skinType: "Sensitive" },
            { text: "No, my skin feels good", skinType: "Normal" },
            { text: "I have occasional flare-ups", skinType: "Sensitive" },
        ],
        image: "images/q3.png"
    },
    {
        question: "Do you get breakouts from time to time? 🌸",
        answers: [
            { text: "Yes, I do", skinType: "Oily" },
            { text: "No, my skin is usually clear", skinType: "Normal" },
            { text: "I get them occasionally", skinType: "Combination" },
        ],
        image: "images/q4.png"
    },
    {
        question: "How does your skin look in the mirror? 🌼",
        answers: [
            { text: "It can look dull or tired", skinType: "Dehydrated" },
            { text: "It looks bright and healthy", skinType: "Normal" },
            { text: "Sometimes it looks a bit lackluster", skinType: "Combination" },
        ],
        image: "images/q5.png"
    },
];


        const skinTypeRecommendations = {
    "Dry": [
        { name: "HydraGlow Cream", id: 1 },
        { name: "Revive & Renew Serum", id: 9 }
    ],
    "Oily": [
        { name: "PetalSoft Hydrator", id: 4 },
        { name: "Herbal Bliss Scrub", id: 6 }
    ],
    "Combination": [
        { name: "Velvet Veil Moisturizer", id: 3 },
        { name: "Berry Bright Scrub", id: 7 }
    ],
    "Normal": [
        { name: "SilkSerenity Lotion", id: 2 },
        { name: "SilkGlow Exfoliating Scrub", id: 5 }
    ],
    "Sensitive": [
        { name: "PetalSoft Hydrator", id: 4 },
        { name: "Calm & Soothe Serum", id: 11 }
    ],
    "Dehydrated": [
        { name: "HydraGlow Cream", id: 1 },
        { name: "HydraBalance Serum", id: 10 }
    ]
};


        let currentQuestionIndex = 0;
        let selectedSkinType = '';

        const questionElement = document.getElementById('question');
        const answersElement = document.getElementById('answers');
        const resultElement = document.getElementById('result');
        const skinTypeElement = document.getElementById('skin-type');
        const productCardsElement = document.getElementById('product-cards');
        const questionImageElement = document.getElementById('question-image');

        function startQuiz() {
            currentQuestionIndex = 0;
            resultElement.classList.add('hidden');
            showQuestion(questions[currentQuestionIndex]);
        }

        function showQuestion(question) {
    questionElement.innerText = question.question;
    answersElement.innerHTML = '';
    questionImageElement.src = question.image; 

    question.answers.forEach((answer, index) => {
        const btn = document.createElement('div');
        btn.className = 'btn'; 
        btn.innerText = answer.text;
        btn.id = `answer-${currentQuestionIndex}-${index}`; 
        btn.addEventListener('click', () => selectAnswer(answer.skinType));
        answersElement.appendChild(btn);
    });
}

        function selectAnswer(skinType) {
            selectedSkinType = skinType;
            currentQuestionIndex++;
            if (currentQuestionIndex < questions.length) {
                showQuestion(questions[currentQuestionIndex]);
            } else {
                showResult();
            }
        }

        function showResult() {
            questionElement.classList.add('hidden');
            answersElement.classList.add('hidden');
            questionImageElement.classList.add('hidden');
            resultElement.classList.remove('hidden');

            skinTypeElement.innerText = selectedSkinType;
            showRecommendedProducts(selectedSkinType);
        }

        function showRecommendedProducts(skinType) {
    const products = skinTypeRecommendations[skinType] || [];
    productCardsElement.innerHTML = ''; 
    products.forEach((product) => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.innerText = product.name; 
        card.setAttribute('data-id', product.id); 
        card.id = `product-${product.id}`; 
        productCardsElement.appendChild(card);

        card.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            window.location.href = `product.php?id=${productId}`; 
        });
    });
}



      
const productCards = document.querySelectorAll('.product-card');


productCards.forEach(card => {
    card.addEventListener('click', function() {
        const productId = this.getAttribute('data-id'); 
        window.location.href = `product.php?id=${productId}`;
    });
});

    document.getElementById('restart-button').addEventListener('click', () => {
        location.reload();
    });    
        startQuiz();
    </script>
</body>
</html>
<?php include 'footer.php'; ?>