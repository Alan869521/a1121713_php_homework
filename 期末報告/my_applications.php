<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli(
    "sql303.infinityfree.com",
    "if0_41808875",
    "N1Zv5a8p2pvw",
    "if0_41808875_sql",
    3306
);

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    applications.id AS application_id,
    applications.created_at AS apply_time,
    tutor_requests.area,
    tutor_requests.target,
    tutor_requests.subject,
    tutor_requests.hourly_rate,
    tutor_requests.online,
    tutor_requests.description
FROM applications
INNER JOIN tutor_requests
ON applications.tutor_request_id = tutor_requests.id
WHERE applications.tutor_user_id = ?
ORDER BY applications.id DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL 錯誤：" . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>我的應徵紀錄</title>

<style>
body {
    margin: 0;
    font-family: Arial, "Microsoft JhengHei", sans-serif;
    background: #f4f6fb;
}

.navbar {
    background: white;
    padding: 18px 50px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 5px 18px rgba(0,0,0,0.12);
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.navbar a {
    margin-left: 10px;
    padding: 10px 16px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
}

.container {
    max-width: 1000px;
    margin: 45px auto;
    padding: 0 20px;
}

.title-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 35px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.record-card {
    background: white;
    padding: 28px;
    border-radius: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.1);
}

.record-card h3 {
    margin-top: 0;
    color: #333;
}

.record-card p {
    margin: 8px 0;
    color: #444;
    line-height: 1.7;
}

.empty {
    background: white;
    padding: 35px;
    border-radius: 18px;
    text-align: center;
    color: #777;
}

.btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 18px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">📚 家教媒合平台</div>
    <div>
        <a href="user.php">回使用者首頁</a>
        <a href="logout.php">登出</a>
    </div>
</div>

<div class="container">

    <div class="title-card">
        <h1>📌 我的應徵紀錄</h1>
        <p>這裡會顯示你曾經應徵過的學生需求。</p>
    </div>

    <?php if ($result->num_rows > 0): ?>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="record-card">
                <h3><?php echo htmlspecialchars($row['subject']); ?></h3>

                <p><strong>需求地區：</strong><?php echo htmlspecialchars($row['area']); ?></p>
                <p><strong>家教對象：</strong><?php echo htmlspecialchars($row['target']); ?></p>
                <p><strong>需求科目：</strong><?php echo htmlspecialchars($row['subject']); ?></p>
                <p><strong>時薪：</strong><?php echo htmlspecialchars($row['hourly_rate']); ?> 元</p>
                <p><strong>可不可線上：</strong><?php echo htmlspecialchars($row['online']); ?></p>
                <p><strong>需求說明：</strong><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                <p><strong>應徵時間：</strong><?php echo htmlspecialchars($row['apply_time']); ?></p>
            </div>
        <?php endwhile; ?>

    <?php else: ?>

        <div class="empty">
            <h2>目前沒有應徵紀錄</h2>
            <p>你可以到「尋找學生」查看可應徵的家教需求。</p>
            <a href="user.php" class="btn">回首頁</a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>