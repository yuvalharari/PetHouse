<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Jerusalem");

$host = 'sql112.byetcluster.com';
$user = 'b16_39092494';
$password = 'Yuval123';
$dbname = 'b16_39092494_pethouse';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_name = $_POST['pet_name'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $age = (int)($_POST['age'] ?? 0);
    $phone = $_POST['phone'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $timestamp = date("Y-m-d H:i:s");
    $animal_type = 'חתול';

    $stmt = $conn->prepare("INSERT INTO adoption_requests (pet_name, full_name, age, phone, experience, notes, submission_time, animal_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssss", $pet_name, $full_name, $age, $phone, $experience, $notes, $timestamp, $animal_type);

    if ($stmt->execute()) {
        $success_message = "הבקשה נשלחה בהצלחה";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>PetHouse - חתולים לאימוץ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            direction: rtl;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fffaf2;
        }

        .main-content {
            display: flex;
            flex-direction: row;
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

        h1, h2, h3 {
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

        .success {
            text-align: center;
            color: green;
            margin-bottom: 15px;
            font-weight: bold;
        }

        #catFacts {
            margin: 40px auto;
            font-size: 18px;
			text-align: center;
            background: #fff3e0;
            padding: 20px;
            border-radius: 10px;
            max-width: 100%;
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

<?php
$cats = [
    ["name" => "רינה", "age" => 2, "gender" => "נקבה", "desc" => "חתולה מתלטפת ושקטה"],
    ["name" => "ליצי", "age" => 1, "gender" => "נקבה", "desc" => "סקרנית ואנרגטית"],
    ["name" => "מוקה", "age" => 3, "gender" => "זכר", "desc" => "חברותי מאוד"],
    ["name" => "תותי", "age" => 2, "gender" => "נקבה", "desc" => "מתאימה לבית עם ילדים"],
    ["name" => "אלפונסו", "age" => 4, "gender" => "זכר", "desc" => "רגוע ונקי"],
    ["name" => "לוני", "age" => 1, "gender" => "נקבה", "desc" => "משחקת עם כל אחד"]
];

$catNames = array_column($cats, 'name');
sort($catNames, SORT_LOCALE_STRING);
?>

<div class="main-content">
<div class="sidebar-wrapper">
    <div class="sidebar">
        <h2>טופס בקשת אימוץ</h2>

        <?php if (!empty($success_message)): ?>
            <div class="success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>בחר חתול:</label>
            <select name="pet_name" required>
                <option value="">-- בחר חתול --</option>
                <?php foreach ($catNames as $name): ?>
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
        <h1>חתולים שמחפשים בית 😺</h1>
        <div style="overflow-x:auto;">
            <table>
                <tr>
                    <th>תמונה</th>
                    <th>שם</th>
                    <th>מין</th>
                    <th>גיל</th>
                    <th>תיאור</th>
                </tr>
                <?php foreach ($cats as $cat): ?>
                    <?php $img = "images/" . strtolower($cat["name"]) . ".jpg"; ?>
                    <tr>
                        <td><img src="<?= $img; ?>" alt="<?= $cat["name"]; ?>"></td>
                        <td><?= $cat["name"]; ?></td>
                        <td><?= $cat["gender"]; ?></td>
                        <td><?= $cat["age"]; ?></td>
                        <td><?= $cat["desc"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div id="catFacts">
            <h3>עובדות מעניינות על חתולים</h3>
            <ul id="factList"></ul>
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

    const facts = [
        "חתולים ישנים בממוצע 12–16 שעות ביום",
        "חתולים מתקשרים בעיקר בשפת גוף",
        "החתול מזהה את שמו אך מתעלם לפעמים",
        "רפרוף בזנב מסמן סקרנות או שמחה",
        "חתולים אוהבים מקומות סגורים כי זה נותן להם ביטחון"
    ];

    const list = document.getElementById("factList");
    facts.forEach(fact => {
        const li = document.createElement("li");
        li.textContent = fact;
        list.appendChild(li);
    });
</script>
</body>
</html>
