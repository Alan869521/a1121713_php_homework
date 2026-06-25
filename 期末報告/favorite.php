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

if (!isset($_GET['tutor_id'])) {
    header("Location: user.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$tutor_id = $_GET['tutor_id'];

$sql = "INSERT IGNORE INTO favorites (user_id, tutor_id) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL 錯誤：" . $conn->error);
}

$stmt->bind_param("ii", $user_id, $tutor_id);

if ($stmt->execute()) {
    echo "<script>
            alert('收藏成功！');
            window.location.href='user.php';
          </script>";
} else {
    echo "收藏失敗：" . $stmt->error;
}
?>