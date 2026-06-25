<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    header("Location: indexcheck.php");
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

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';

if ($type == "tutor") {
    $table = "tutors";
} elseif ($type == "request") {
    $table = "tutor_requests";
} else {
    header("Location: admin.php");
    exit();
}

if ($action == "approve") {
    $status = "已通過";

    $sql = "UPDATE $table SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

} elseif ($action == "reject") {
    $status = "已拒絕";

    $sql = "UPDATE $table SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

} elseif ($action == "delete") {

    $sql = "DELETE FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin.php");
exit();
?>