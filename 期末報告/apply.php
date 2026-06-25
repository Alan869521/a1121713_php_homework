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

$tutor_request_id = $_GET['id'];
$tutor_user_id = $_SESSION['user_id'];

/* 防止重複應徵 */

$check_sql = "
SELECT * FROM applications
WHERE tutor_request_id = ?
AND tutor_user_id = ?
";

$check_stmt = $conn->prepare($check_sql);

$check_stmt->bind_param(
    "ii",
    $tutor_request_id,
    $tutor_user_id
);

$check_stmt->execute();

$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {

    echo "
    <script>
        alert('你已經應徵過此案件');
        window.location.href='user.php';
    </script>
    ";

    exit();
}

/* 新增應徵 */

$sql = "
INSERT INTO applications
(tutor_request_id, tutor_user_id)
VALUES (?, ?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $tutor_request_id,
    $tutor_user_id
);

if ($stmt->execute()) {

    echo "
    <script>
        alert('應徵成功！');
        window.location.href='user.php';
    </script>
    ";

} else {

    echo "應徵失敗：" . $stmt->error;
}
?>