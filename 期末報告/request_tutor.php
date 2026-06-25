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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $area = $_POST['area'];
    $target = $_POST['target'];
    $subject = $_POST['subject'];
    $hourly_rate = $_POST['hourly_rate'];
    $online = $_POST['online'];
    $description = $_POST['description'];

    $sql = "INSERT INTO tutor_requests 
            (user_id, area, target, subject, hourly_rate, online, description)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL 錯誤：" . $conn->error);
    }

    $stmt->bind_param(
        "isssiss",
        $user_id,
        $area,
        $target,
        $subject,
        $hourly_rate,
        $online,
        $description
    );

    if ($stmt->execute()) {
        echo "<script>
                alert('徵求家教發布成功！');
                window.location.href='user.php';
              </script>";
        exit();
    } else {
        echo '發布失敗：' . $stmt->error;
    }
}
?>