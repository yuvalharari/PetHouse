<?php
session_start();

$USERNAME = 'admin';
$PASSWORD = '1234';

$host = 'sql112.byetcluster.com';
$user = 'b16_39092494';
$password = 'Yuval123';
$dbname = 'b16_39092494_pethouse';
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    if ($_POST['username'] === $USERNAME && $_POST['password'] === $PASSWORD) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = "שם משתמש או סיסמה שגויים.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

$filter = $_GET['type'] ?? 'all';
$whereClause = "";
if ($filter === 'dog') {
    $whereClause = "WHERE animal_type = 'כלב'";
} elseif ($filter === 'cat') {
    $whereClause = "WHERE animal_type = 'חתול'";
}
?>
<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="UTF-8">
    <title>Admin - PetHouse</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f2f2f2;
            text-align: center;
            direction: rtl;
            margin: 0;
            padding: 0;
        }

        form {
            background: #fff;
            padding: 20px;
            margin: 50px auto;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input {
            margin-bottom: 10px;
            width: 90%;
            padding: 8px;
            font-size: 16px;
        }

        .table-container {
            overflow-x: auto;
            margin: 20px;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 95%;
            background: #fff;
            min-width: 800px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            text-align: center;
        }

        th {
            background: #eee;
        }

        .logout, .filter {
            margin-top: 20px;
        }

        .filter a {
            margin: 0 10px;
            text-decoration: none;
            color: #0077cc;
        }

        .filter a:hover {
            text-decoration: underline;
        }

        #filterButton {
            margin-top: 10px;
            padding: 7px 14px;
            font-weight: bold;
            cursor: pointer;
            background-color: #ff9933;
            border: none;
            color: white;
            border-radius: 5px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            form {
                width: 90%;
                margin: 30px auto;
            }

            input {
                width: 95%;
                font-size: 15px;
            }

            table {
                font-size: 13px;
                min-width: 700px;
            }

            .filter, .logout {
                padding: 10px;
                font-size: 15px;
            }

            #filterButton {
                width: 90%;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            table {
                font-size: 12px;
                min-width: 600px;
            }

            th, td {
                padding: 6px;
            }

            .filter a {
                display: block;
                margin: 8px 0;
            }
        }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['logged_in'])): ?>
    <form method="POST">
        <h2>התחברות אדמין</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <input type="text" name="username" placeholder="שם משתמש"><br>
        <input type="password" name="password" placeholder="סיסמה"><br>
        <button type="submit">התחבר</button>
    </form>
<?php else: ?>
    <h2>בקשות אימוץ</h2>

    <div class="filter">
        סינון: 
        <a href="admin.php?type=all">הכל</a> |
        <a href="admin.php?type=dog">כלבים</a> |
        <a href="admin.php?type=cat">חתולים</a>
        <br>
        <button id="filterButton">חפש לפי טלפון</button>
    </div>

    <div class="table-container">
        <table id="adoptionTable">
            <tr>
                <th>מס'</th>
                <th>סוג</th>
                <th>שם</th>
                <th>שם מלא</th>
                <th>גיל</th>
                <th>טלפון</th>
                <th>ניסיון</th>
                <th>הערות</th>
                <th>זמן</th>
            </tr>
            <?php
            $res = $conn->query("SELECT * FROM adoption_requests $whereClause ORDER BY submission_time DESC");
            while ($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['animal_type']; ?></td>
                    <td><?= htmlspecialchars($row['pet_name']); ?></td>
                    <td><?= htmlspecialchars($row['full_name']); ?></td>
                    <td><?= $row['age']; ?></td>
                    <td><?= htmlspecialchars($row['phone']); ?></td>
                    <td><?= htmlspecialchars($row['experience']); ?></td>
                    <td><?= nl2br(htmlspecialchars($row['notes'])); ?></td>
                    <td><?= $row['submission_time']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="logout">
        <a href="admin.php?logout=1">התנתק</a>
    </div>
<?php endif; ?>

<script>
    document.getElementById('filterButton')?.addEventListener('click', function () {
        const phone = prompt("הזן מספר טלפון לחיפוש:");
        if (!phone) return;

        const rows = document.querySelectorAll("#adoptionTable tr");
        for (let i = 1; i < rows.length; i++) {
            const cell = rows[i].cells[5]; // טור טלפון
            if (cell && !cell.textContent.includes(phone)) {
                rows[i].style.display = "none";
            } else {
                rows[i].style.display = "";
            }
        }
    });
</script>

</body>
</html>
