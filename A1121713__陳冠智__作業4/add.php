<?php
require_once 'db_config.php'; // 把開門的鑰匙拿過來

if (isset($_POST['add_email'])) {
    $email = $_POST['user_email']; // 拿到使用者填的資料

    // SQL 語法：把資料塞進 recipients 資料表的 email 欄位
    $sql = "INSERT INTO recipients (email) VALUES ('$email')";

    // 叫 $conn 去執行 (query) 這串語法
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('成功存入！');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>新增 Email</title></head>
<body>
    <h2>➕ 建構資料庫：新增收件者</h2>
    <form method="POST">
        <input type="email" name="user_email" placeholder="輸入 Email 地址" required>
        <button type="submit" name="add_email">確認新增</button>
    </form>
    <br>
    <a href="send.php">下一步：去寄信控制台 →</a>
</body>
</html>