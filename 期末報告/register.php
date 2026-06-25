<?php
session_start();

$conn = new mysqli(
    "sql303.infinityfree.com",
    "if0_41808875",
    "N1Zv5a8p2pvw",
    "if0_41808875_sql",
    3306
);

if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 表單送出
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = "user"; // 預設使用者

    // 檢查帳號是否存在
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "此 Email 已被註冊";
    } else {

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "註冊失敗";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>註冊</title>
<style>
body {
    margin: 0;
    font-family: Arial;
    background: linear-gradient(135deg, #667eea, #764ba2);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.card {
    background: white;
    padding: 40px;
    border-radius: 20px;
    width: 350px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    text-align: center;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: #667eea;
    color: white;
    font-size: 16px;
}

.error {
    color: red;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<div class="card">
    <h2>📝 註冊帳號</h2>

    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="姓名" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="密碼" required>

        <button type="submit">註冊</button>
    </form>
</div>

</body>
</html>