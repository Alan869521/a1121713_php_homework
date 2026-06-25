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
    header("Location: my_favorites.php");
    exit();
}

$favorite_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM favorites WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $favorite_id, $user_id);

if ($stmt->execute()) {
    echo "<script>
            alert('已取消收藏');
            window.location.href='my_favorites.php';
          </script>";
} else {
    echo "取消收藏失敗：" . $stmt->error;
}
?>