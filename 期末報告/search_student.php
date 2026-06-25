<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$conn = new mysqli( "sql303.infinityfree.com", "if0_41808875", "N1Zv5a8p2pvw", "if0_41808875_sql", 3306 );

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 取得搜尋條件
$area = $_GET['area'] ?? '';
$target = $_GET['target'] ?? '';
$subject = $_GET['subject'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$online = $_GET['online'] ?? '';

$sql = "SELECT * FROM tutor_requests WHERE 1=1";
$params = [];
$types = "";

// 動態條件
if ($area != "") {
    $sql .= " AND area LIKE ?";
    $params[] = "%$area%";
    $types .= "s";
}

if ($target != "") {
    $sql .= " AND target = ?";
    $params[] = $target;
    $types .= "s";
}

if ($subject != "") {
    $sql .= " AND subject LIKE ?";
    $params[] = "%$subject%";
    $types .= "s";
}

if ($min_price != "") {
    $sql .= " AND hourly_rate >= ?";
    $params[] = $min_price;
    $types .= "i";
}

if ($online != "") {
    $sql .= " AND online = ?";
    $params[] = $online;
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>搜尋學生需求</title>

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
}

.navbar a {
    text-decoration: none;
    background: #667eea;
    color: white;
    padding: 10px 16px;
    border-radius: 10px;
    margin-left: 10px;
}

.container {
    max-width: 1100px;
    margin: 45px auto;
    padding: 0 20px;
}

.title-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
}

.result-card {
    background: white;
    padding: 28px;
    border-radius: 18px;
    margin-bottom: 20px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.1);
}

.result-card h3 {
    margin-top: 0;
}

.info {
    line-height: 1.8;
}

.empty {
    background: white;
    padding: 35px;
    border-radius: 18px;
    text-align: center;
}

.btn {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    background: #667eea;
    color: white;
    padding: 10px 18px;
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">📚 家教媒合平台</div>
    <div>
        <a href="user.php">回首頁</a>
        <a href="logout.php">登出</a>
    </div>
</div>

<div class="container">

    <div class="title-card">
        <h1>👨‍🎓 學生需求搜尋結果</h1>
        <p>以下是符合條件的徵求家教資訊</p>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="result-card">
                <h3><?php echo htmlspecialchars($row['subject']); ?></h3>

                <div class="info">
                    <p><strong>地區：</strong><?php echo htmlspecialchars($row['area']); ?></p>
                    <p><strong>對象：</strong><?php echo htmlspecialchars($row['target']); ?></p>
                    <p><strong>時薪：</strong><?php echo htmlspecialchars($row['hourly_rate']); ?> 元</p>
                    <p><strong>是否可線上：</strong><?php echo htmlspecialchars($row['online']); ?></p>
                    <p><strong>需求說明：</strong><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                </div>

                <a href="#" class="btn">我要應徵</a>
            </div>
        <?php endwhile; ?>

    <?php else: ?>
        <div class="empty">
            <h2>沒有符合條件的學生需求</h2>
            <a href="user.php" class="btn">重新搜尋</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>