<?php
$message = "";
date_default_timezone_set("Asia/Jerusalem");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'sql112.byetcluster.com';
    $user = 'b16_39092494';
    $password = 'Yuval123';
    $dbname = 'b16_39092494_pethouse';

    $conn = new mysqli($host, $user, $password, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

    $pet_name = $_POST['pet_name'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $age = (int)($_POST['age'] ?? 0);
    $phone = $_POST['phone'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $timestamp = date("Y-m-d H:i:s");
    $animal_type = 'כלב';

    $stmt = $conn->prepare("INSERT INTO adoption_requests (pet_name, full_name, age, phone, experience, notes, submission_time, animal_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssss", $pet_name, $full_name, $age, $phone, $experience, $notes, $timestamp, $animal_type);

    if ($stmt->execute()) {
        $message = "<div style='color: green; font-weight: bold; text-align: center;'>הבקשה נשלחה בהצלחה</div>";
    } else {
        $message = "<div style='color: red; text-align: center;'>אירעה שגיאה בשליחת הבקשה.</div>";
    }

    $stmt->close();
    $conn->close();
}

$dogs = [
    ["name" => "בובי", "age" => 3, "gender" => "זכר", "desc" => "כלב שמח ואנרגטי"],
    ["name" => "בלה", "age" => 2, "gender" => "נקבה", "desc" => "ידידותית ואוהבת ליטופים"],
    ["name" => "נילי", "age" => 4, "gender" => "זכר", "desc" => "שקט ונאמן"],
    ["name" => "לונה", "age" => 1, "gender" => "נקבה", "desc" => "גורה סקרנית ומתוקה"],
    ["name" => "מוקי", "age" => 5, "gender" => "זכר", "desc" => "כלב שמירה מצוין"],
    ["name" => "מיילו", "age" => 2, "gender" => "זכר", "desc" => "חכם ומלא חיים"],
    ["name" => "ניקה", "age" => 3, "gender" => "נקבה", "desc" => "מסתדרת עם ילדים"],
    ["name" => "רוקי", "age" => 4, "gender" => "זכר", "desc" => "כלב רגוע ואוהב טבע"],
    ["name" => "שוקי", "age" => 2, "gender" => "נקבה", "desc" => "קטנה, מלאת אהבה"]
];

$dogNames = array_column($dogs, 'name');
sort($dogNames, SORT_LOCALE_STRING);
?>
<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>PetHouse - כלבים לאימוץ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            direction: rtl;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fffaf2;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            display: flex;
            flex: 1;
            align-items: stretch;
        }

        .sidebar-wrapper {
    background-color: #fff3e0;
    width: 30%;
}

.sidebar {
    padding: 20px;
    box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 20px;
    align-self: flex-start;
    height: fit-content;
}

        .content {
            width: 70%;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        img {
            width: 100px;
            border-radius: 10px;
        }

        h1, h2 {
            text-align: center;
        }

        input, select, textarea, button {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #ff9933;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #e67e00;
        }

        @media (max-width: 992px) {
    .main-content {
        flex-direction: column;
    }

    .sidebar-wrapper {
        width: 100%;
        background-color: #fff3e0;
        padding: 0;
    }

    .sidebar {
        position: static; /* מבטל sticky בטאבלט */
        width: 100%;
        height: auto;
        padding: 20px;
    }

    .content {
        width: 100%;
    }

    img {
        width: 80px;
    }
}

@media (max-width: 480px) {
    th, td {
        padding: 6px;
        font-size: 13px;
    }

    img {
        width: 60px;
    }

    input, select, textarea {
        font-size: 14px;
    }

    .sidebar {
        overflow-y: auto;
        max-height: 400px; /* אפשר להתאים */
    }
}

        .tips-box {
            margin: 40px auto;
            font-size: 18px;
			text-align: center;
            background: #fff3e0;
            padding: 20px;
            border-radius: 10px;
            max-width: 100%;
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

<div class="main-content">
<div class="sidebar-wrapper">
    <div class="sidebar">
        <h2>טופס בקשת אימוץ</h2>
        <?= $message; ?>
        <form method="POST">
            <label>בחר כלב:</label>
            <select name="pet_name" required>
                <option value="">-- בחר כלב --</option>
                <?php foreach ($dogNames as $name): ?>
                    <option value="<?= $name; ?>"><?= $name; ?></option>
                <?php endforeach; ?>
            </select>
            <label>שם מלא:</label>
            <input type="text" name="full_name" required>
            <label>גיל:</label>
            <input type="number" name="age" required>
            <label>טלפון:</label>
            <input type="text" name="phone" required>
            <label>ניסיון קודם:</label>
            <select name="experience">
                <option value="כן">כן</option>
                <option value="לא">לא</option>
            </select>
            <label>הערות:</label>
            <textarea name="notes" rows="4"></textarea>
            <button type="submit">שלח בקשה</button>
        </form>
    </div>
	    </div>


    <div class="content">
        <h1>כלבים שמחפשים בית 🐶</h1>
        <div style="overflow-x:auto;">
            <table>
                <tr>
                    <th>תמונה</th>
                    <th>שם</th>
                    <th>מין</th>
                    <th>גיל</th>
                    <th>תיאור</th>
                </tr>
                <?php foreach ($dogs as $dog): ?>
                    <?php $img = "images/" . strtolower($dog["name"]) . ".jpg"; ?>
                    <tr>
                        <td><img src="<?= $img; ?>" alt="<?= $dog["name"]; ?>"></td>
                        <td><?= $dog["name"]; ?></td>
                        <td><?= $dog["gender"]; ?></td>
                        <td><?= $dog["age"]; ?></td>
                        <td><?= $dog["desc"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="tips-box">
            <h3>טיפים לאימוץ כלב</h3>
            <ul id="tipList"></ul>
        </div>
    </div>
</div>

<footer>
    <img src="logo.png" alt="לוגו PetHouse" class="footer-logo"><br>
    <p>צור קשר: info@pethouse.com | 050-1234567</p>
</footer>

<script>
    function openAdmin() {
        window.open('admin.php', '_blank', 'width=800,height=600');
    }

    const tips = [
        "טיול פעמיים ביום חיוני לכלב שמח",
        "תמיד דאג למים טריים",
        "צעצועים מצמצמים חרדה לכלבים מאומצים"
    ];

    const ul = document.getElementById("tipList");
    tips.forEach(tip => {
        const li = document.createElement("li");
        li.textContent = tip;
        ul.appendChild(li);
    });
</script>
</body>
</html>
