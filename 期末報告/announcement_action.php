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

$conn->set_charset("utf8mb4");

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action == "add") {

    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("
        INSERT INTO announcements
        (title, content)
        VALUES (?, ?)
    ");

    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();

}

elseif ($action == "update") {

    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("
        UPDATE announcements
        SET title=?, content=?
        WHERE id=?
    ");

    $stmt->bind_param("ssi", $title, $content, $id);
    $stmt->execute();

}

elseif ($action == "delete") {

    $id = $_GET['id'];

    $stmt = $conn->prepare("
        DELETE FROM announcements
        WHERE id=?
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin.php");
exit();
?>