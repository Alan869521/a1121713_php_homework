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

    tutor_requests.id AS request_id,
    tutor_requests.area AS request_area,
    tutor_requests.target AS request_target,
    tutor_requests.subject AS request_subject,
    tutor_requests.hourly_rate AS request_hourly_rate,
    tutor_requests.online AS request_online,
    tutor_requests.description AS request_description,

    users.name AS applicant_name,
    users.email AS applicant_email,

    tutors.area AS tutor_area,
    tutors.target AS tutor_target,
    tutors.subject AS tutor_subject,
    tutors.hourly_rate AS tutor_hourly_rate,
    tutors.online AS tutor_online,
    tutors.intro AS tutor_intro

FROM applications
INNER JOIN tutor_requests
ON applications.tutor_request_id = tutor_requests.id

INNER JOIN users
ON applications.tutor_user_id = users.id

LEFT JOIN tutors
ON applications.tutor_user_id = tutors.user_id

WHERE tutor_requests.user_id = ?

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
<title>收到的應徵</title>

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
    max-width: 1100px;
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

.application-card {
    background: white;
    padding: 30px;
    border-radius: 18px;
    margin-bottom: 22px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.1);
}

.application-card h3 {
    margin-top: 0;
    color: #333;
}

.section-box {
    background: #f7f7ff;
    border: 1px solid #ddd;
    border-radius: 15px;
    padding: 20px;
    margin-top: 18px;
}

.section-box h4 {
    margin-top: 0;
    color: #333;
}

.section-box p {
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

.btn:hover,
.navbar a:hover {
    opacity: 0.85;
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
        <h1>📥 收到的應徵</h1>
        <p>這裡會顯示你的徵求家教貼文收到哪些老師應徵。</p>
    </div>

    <?php if ($result->num_rows > 0): ?>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="application-card">

                <h3>應徵者：<?php echo htmlspecialchars($row['applicant_name']); ?></h3>
                <p><strong>Email：</strong><?php echo htmlspecialchars($row['applicant_email']); ?></p>
                <p><strong>應徵時間：</strong><?php echo htmlspecialchars($row['apply_time']); ?></p>

                <div class="section-box">
                    <h4>📌 你的徵求內容</h4>
                    <p><strong>地區：</strong><?php echo htmlspecialchars($row['request_area']); ?></p>
                    <p><strong>對象：</strong><?php echo htmlspecialchars($row['request_target']); ?></p>
                    <p><strong>科目：</strong><?php echo htmlspecialchars($row['request_subject']); ?></p>
                    <p><strong>時薪：</strong><?php echo htmlspecialchars($row['request_hourly_rate']); ?> 元</p>
                    <p><strong>可不可線上：</strong><?php echo htmlspecialchars($row['request_online']); ?></p>
                    <p><strong>需求說明：</strong><?php echo nl2br(htmlspecialchars($row['request_description'])); ?></p>
                </div>

                <div class="section-box">
                    <h4>👨‍🏫 應徵老師資料</h4>

                    <?php if (!empty($row['tutor_subject'])): ?>
                        <p><strong>教學地區：</strong><?php echo htmlspecialchars($row['tutor_area']); ?></p>
                        <p><strong>可教對象：</strong><?php echo htmlspecialchars($row['tutor_target']); ?></p>
                        <p><strong>可教科目：</strong><?php echo htmlspecialchars($row['tutor_subject']); ?></p>
                        <p><strong>希望時薪：</strong><?php echo htmlspecialchars($row['tutor_hourly_rate']); ?> 元</p>
                        <p><strong>可不可線上：</strong><?php echo htmlspecialchars($row['tutor_online']); ?></p>
                        <p><strong>自我介紹：</strong><?php echo nl2br(htmlspecialchars($row['tutor_intro'])); ?></p>
                    <?php else: ?>
                        <p>此應徵者尚未填寫家教註冊資料。</p>
                    <?php endif; ?>
                </div>

            </div>
        <?php endwhile; ?>

    <?php else: ?>

        <div class="empty">
            <h2>目前還沒有收到應徵</h2>
            <p>你發布的徵求家教目前尚未有人應徵。</p>
            <a href="user.php" class="btn">回首頁</a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>