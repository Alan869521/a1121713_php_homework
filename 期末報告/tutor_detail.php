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

if (!isset($_GET['id'])) {
    header("Location: user.php");
    exit();
}

$id = $_GET['id'];

$sql = "
SELECT 
    tutors.*,
    users.email,
    profiles.phone,
    profiles.address,
    profiles.gender,
    profiles.birthday,
    profiles.bio,
    profiles.avatar
FROM tutors
INNER JOIN users
ON tutors.user_id = users.id
LEFT JOIN profiles
ON tutors.user_id = profiles.user_id
WHERE tutors.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "找不到此家教資料";
    exit();
}

$tutor = $result->fetch_assoc();

$avatar = !empty($tutor['avatar']) ? $tutor['avatar'] : "uploads/default.png";
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>家教詳細資料</title>

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
    text-decoration: none;
    background: #667eea;
    color: white;
    padding: 10px 16px;
    border-radius: 10px;
    margin-left: 10px;
}

.container {
    max-width: 900px;
    margin: 45px auto;
    padding: 0 20px;
}

.profile-card {
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 30px;
    border-radius: 18px;
    margin-bottom: 25px;
    text-align: center;
}

.avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    margin-bottom: 15px;
}

.info-box {
    background: #f7f7ff;
    padding: 22px;
    border-radius: 15px;
    margin-bottom: 18px;
    border: 1px solid #ddd;
}

.info-box p {
    font-size: 16px;
    line-height: 1.8;
    color: #444;
    margin: 8px 0;
}

.intro {
    white-space: pre-line;
}

.back-btn {
    display: inline-block;
    margin-top: 20px;
    background: #667eea;
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
}

.back-btn:hover,
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

    <div class="profile-card">

        <div class="header">
            <img src="<?php echo htmlspecialchars($avatar); ?>" class="avatar">
            <h1><?php echo htmlspecialchars($tutor['name']); ?></h1>
            <p>家教詳細資料</p>
        </div>

        <div class="info-box">
            <h2>家教資料</h2>
            <p><strong>教學地區：</strong><?php echo htmlspecialchars($tutor['area']); ?></p>
            <p><strong>家教對象：</strong><?php echo htmlspecialchars($tutor['target']); ?></p>
            <p><strong>教學科目：</strong><?php echo htmlspecialchars($tutor['subject']); ?></p>
            <p><strong>最低時薪：</strong><?php echo htmlspecialchars($tutor['hourly_rate']); ?> 元</p>
            <p><strong>可不可線上：</strong><?php echo htmlspecialchars($tutor['online']); ?></p>
            <p><strong>註冊時間：</strong><?php echo htmlspecialchars($tutor['created_at']); ?></p>
        </div>

        <div class="info-box">
            <h2>個人資料</h2>
            <p><strong>Email：</strong><?php echo htmlspecialchars($tutor['email']); ?></p>
            <p><strong>電話：</strong><?php echo htmlspecialchars($tutor['phone'] ?? '尚未填寫'); ?></p>
            <p><strong>地址：</strong><?php echo htmlspecialchars($tutor['address'] ?? '尚未填寫'); ?></p>
            <p><strong>性別：</strong><?php echo htmlspecialchars($tutor['gender'] ?? '尚未填寫'); ?></p>
            <p><strong>生日：</strong><?php echo htmlspecialchars($tutor['birthday'] ?? '尚未填寫'); ?></p>
        </div>

        <div class="info-box">
            <h2>家教自我介紹</h2>
            <p class="intro"><?php echo nl2br(htmlspecialchars($tutor['intro'] ?? '尚未填寫')); ?></p>
        </div>

        <div class="info-box">
            <h2>個人簡介</h2>
            <p class="intro"><?php echo nl2br(htmlspecialchars($tutor['bio'] ?? '尚未填寫')); ?></p>
        </div>

        <a href="user.php" class="back-btn">返回</a>

    </div>

</div>

</body>
</html>