<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
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
    $subject_name = $_POST['subject_name'];

    $stmt = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
    $stmt->bind_param("s", $subject_name);
    $stmt->execute();

} elseif ($action == "update") {
    $id = $_POST['id'];
    $subject_name = $_POST['subject_name'];

    $stmt = $conn->prepare("UPDATE subjects SET subject_name=? WHERE id=?");
    $stmt->bind_param("si", $subject_name, $id);
    $stmt->execute();

} elseif ($action == "delete") {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM subjects WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin.php");
exit();
?>