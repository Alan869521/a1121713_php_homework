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
    favorites.id AS favorite_id,
    favorites.created_at AS favorite_time,
    tutors.id AS tutor_id,
    tutors.name,
    tutors.area,
    tutors.target,
    tutors.subject,
    tutors.hourly_rate,
    tutors.online,
    tutors.intro
FROM favorites
INNER JOIN tutors
ON favorites.tutor_id = tutors.id
WHERE favorites.user_id = ?
ORDER BY favorites.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>我的收藏</title>
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
}
.record-card p {
    margin: 8px 0;
    line-height: 1.7;
}
.btn {
    display: inline-block;
    margin-top: 12px;
    padding: 10px 18px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
}
.empty {
    background: white;
    padding: 35px;
    border-radius: 18px;
    text-align: center;
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
        <h1>❤️ 我的收藏</h1>
        <p>這裡會顯示你收藏的家教老師。</p>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="record-card">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><strong>地區：</strong><?php echo htmlspecialchars($row['area']); ?></p>
                <p><strong>對象：</strong><?php echo htmlspecialchars($row['target']); ?></p>
                <p><strong>科目：</strong><?php echo htmlspecialchars($row['subject']); ?></p>
                <p><strong>時薪：</strong><?php echo htmlspecialchars($row['hourly_rate']); ?> 元</p>
                <p><strong>線上：</strong><?php echo htmlspecialchars($row['online']); ?></p>
                <p><strong>介紹：</strong><?php echo nl2br(htmlspecialchars($row['intro'])); ?></p>
                <p><strong>收藏時間：</strong><?php echo htmlspecialchars($row['favorite_time']); ?></p>

                <a class="btn" href="tutor_detail.php?id=<?php echo $row['tutor_id']; ?>">查看詳細資料</a>
                <a class="btn" href="remove_favorite.php?id=<?php echo $row['favorite_id']; ?>">取消收藏</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty">
            <h2>目前沒有收藏</h2>
            <a href="user.php" class="btn">去尋找家教</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>