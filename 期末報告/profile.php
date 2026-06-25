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
    users.name,
    users.email,
    profiles.*
FROM users
LEFT JOIN profiles
ON users.id = profiles.user_id
WHERE users.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

$hasProfile = !empty($profile['id']);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>個人資料</title>

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
    max-width: 900px;
    margin: 45px auto;
    padding: 0 20px;
}

.card {
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.title {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 30px;
    border-radius: 18px;
    margin-bottom: 25px;
}

.avatar-box {
    text-align: center;
    margin-bottom: 25px;
}

.avatar {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #667eea;
    background: #eee;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.form-group {
    text-align: left;
}

.full {
    grid-column: span 2;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #444;
}

input,
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-sizing: border-box;
}

textarea {
    height: 120px;
    resize: none;
}

.btn {
    display: inline-block;
    margin-top: 25px;
    margin-right: 8px;
    padding: 12px 20px;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    color: white;
    cursor: pointer;
}

.save {
    background: #667eea;
}

.delete {
    background: #dc3545;
}

.empty {
    color: #777;
}

.pdf {
    background: #28a745;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .full {
        grid-column: span 1;
    }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">📚 個人資料</div>
    <div>
        <a href="user.php">回首頁</a>
        <a href="logout.php">登出</a>
    </div>
</div>

<div class="container">

    <div class="card">

        <div class="title">
            <h1>個人資料管理</h1>
            <p>你可以新增、修改或刪除自己的個人資料。</p>
        </div>

        <?php
            $avatar = !empty($profile['avatar'])
            ? $profile['avatar']
            : 'uploads/default.png';
        ?>

<img src="<?php echo htmlspecialchars($avatar); ?>" class="avatar">

        <form action="profile_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?php echo $hasProfile ? 'update' : 'add'; ?>">

            <div class="form-grid">

                <div class="form-group">
                    <label>姓名</label>
                    <input type="text" value="<?php echo htmlspecialchars($profile['name']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="<?php echo htmlspecialchars($profile['email']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>電話</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>" placeholder="請輸入電話">
                </div>

                <div class="form-group">
                    <label>性別</label>
                    <select name="gender">
                        <option value="">請選擇</option>
                        <option value="男" <?php if (($profile['gender'] ?? '') == '男') echo 'selected'; ?>>男</option>
                        <option value="女" <?php if (($profile['gender'] ?? '') == '女') echo 'selected'; ?>>女</option>
                        <option value="其他" <?php if (($profile['gender'] ?? '') == '其他') echo 'selected'; ?>>其他</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>生日</label>
                    <input type="date" name="birthday" value="<?php echo htmlspecialchars($profile['birthday'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>大頭貼</label>
                    <input type="file" name="avatar" accept="image/*">
                </div>

                <div class="form-group full">
                    <label>地址</label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($profile['address'] ?? ''); ?>" placeholder="請輸入地址">
                </div>

                <div class="form-group full">
                    <label>自我介紹</label>
                    <textarea name="bio" placeholder="請輸入自我介紹"><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
                </div>

            </div>

            <button class="btn save" type="submit">
                <?php echo $hasProfile ? '修改個人資料' : '新增個人資料'; ?>
            </button>
            <a class="btn pdf" href="profile_pdf.php" target="_blank">匯出 PDF</a>

            <?php if ($hasProfile): ?>
                <a class="btn delete"
                   href="profile_action.php?action=delete"
                   onclick="return confirm('確定要刪除個人資料嗎？')">刪除個人資料</a>
            <?php endif; ?>
        </form>

    </div>

</div>

</body>
</html>