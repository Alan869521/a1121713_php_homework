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
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL 錯誤：" . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {

    if ($password == $row['password']) {

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];

        header("Location: indexcheck.php");
        exit();

    } else {
        echo "密碼錯誤";
    }

} else {
    echo "帳號不存在";
}
?>