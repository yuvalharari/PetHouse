<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>PetHouse - תרומות</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            direction: rtl;
            font-family: Arial, sans-serif;
            background-color: #fffaf2;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #ffcc70;
            text-align: center;
            padding: 20px;
        }

        nav {
            background-color: #f2a365;
            text-align: center;
            padding: 10px;
        }

        nav a {
            margin: 0 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            flex: 1;
            padding: 30px 20px;
            display: flex;
            justify-content: center;
        }

        .form-container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 12px;
            margin-bottom: 60px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background-color: #ff9933;
            color: white;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #e67e00;
        }

        .result {
            background-color: #f7f2e9;
            border-right: 5px solid #ff9933;
            padding: 20px;
            margin-top: 25px;
            border-radius: 10px;
            font-size: 1.1em;
            line-height: 1.6;
        }

        .result ul {
            list-style: none;
            padding: 0;
        }

        .result li {
            margin-bottom: 8px;
        }

        .total-preview {
            font-weight: bold;
            color: #d35400;
            margin-bottom: 15px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
        }

        .footer-logo {
            width: 100px;
            margin-bottom: 10px;
        }

        @media (max-width: 600px) {
            main {
                padding: 20px 10px;
            }

            .form-container {
                padding: 20px;
                margin: 20px auto 60px auto;
            }

            input, select, button {
                font-size: 15px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>PetHouse</h1>
    <p>אמצו את החבר הכי טוב שלכם</p>
</header>

<nav>
    <a href="index.html">דף הבית</a>
    <a href="dogs.php">כלבים לאימוץ</a>
    <a href="cats.php">חתולים לאימוץ</a>
    <a href="faq.html">שאלות ותשובות</a>
    <a href="contact.html">צור קשר</a>
    <a href="about.html">אודות</a>
    <a href="donate.php">תרומות</a>
    <a href="#" onclick="openAdmin()">הנהלה</a>
</nav>

<main>
    <div class="form-container">
        <h2>טופס תרומה</h2>
        <form method="POST">
            <label>שם מלא:</label>
            <input type="text" name="full_name" required>

            <label>סכום תרומה לחודש (₪):</label>
            <input type="number" name="amount" required min="1">

            <label>מספר חודשים:</label>
            <input type="number" name="months" required min="1" max="24">

            <div id="totalPreview" class="total-preview"></div>

            <label>בחר אמצעי תשלום:</label>
            <select name="method" required>
                <option value="כרטיס אשראי">כרטיס אשראי</option>
                <option value="פייפאל">PayPal</option>
                <option value="Bit">Bit</option>
            </select>

            <button type="submit">שלח תרומה</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['full_name']);
            $amount = (float)$_POST['amount'];
            $months = (int)$_POST['months'];
            $method = $_POST['method'];

            function calculateDonation($amount, $months) {
                return $amount * $months;
            }

            $total = calculateDonation($amount, $months);

            $data = [
                "שם מלא" => $name,
                "סכום לחודש" => "₪$amount",
                "מספר חודשים" => $months,
                "אמצעי תשלום" => $method,
                "סה\"כ תרומה" => "₪$total"
            ];

            echo "<div class='result'>";
            echo "<p>" . strtoupper("תודה רבה $name על תרומתך הנדיבה!") . "</p><ul>";
            foreach ($data as $key => $value) {
                echo "<li><strong>$key:</strong> $value</li>";
            }
            echo "</ul></div>";
        }
        ?>
    </div>
</main>

<footer>
    <img src="logo.png" alt="לוגו PetHouse" class="footer-logo"><br>
    <p>צור קשר: info@pethouse.com | 050-1234567</p>
</footer>

<script>
    function openAdmin() {
        window.open('admin.php', '_blank', 'width=800,height=600');
    }

    const amountInput = document.querySelector('input[name="amount"]');
    const monthsInput = document.querySelector('input[name="months"]');
    const totalPreview = document.getElementById('totalPreview');

    function updateTotal() {
        const amount = parseFloat(amountInput.value);
        const months = parseInt(monthsInput.value);
        if (!isNaN(amount) && !isNaN(months)) {
            const total = amount * months;
            totalPreview.textContent = `סה"כ תרומה משוערת: ₪${total}`;
        } else {
            totalPreview.textContent = "";
        }
    }

    amountInput.addEventListener("input", updateTotal);
    monthsInput.addEventListener("input", updateTotal);
</script>

</body>
</html>
