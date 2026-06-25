<?php
session_start();

// 已登入就直接導向
if (isset($_SESSION['user_id'])) {
    header("Location: indexcheck.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>登入 - 家教平台</title>
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

h2 {
    margin-bottom: 20px;
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
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

.register {
    margin-top: 15px;
    display: block;
    text-decoration: none;
    color: #667eea;
}
</style>
</head>

<body>

<div class="card">
    <h2>🔐 使用者登入</h2>

    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="密碼" required>
        <button type="submit">登入</button>
    </form>

    <a href="register.php" class="register">還沒有帳號？點我註冊</a>
</div>

</body>
</html>