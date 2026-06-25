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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status, created_at) VALUES (?, ?, ?, ?, '正常', NOW())");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();

} elseif ($action == "update") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $role, $id);
    $stmt->execute();

} elseif ($action == "delete") {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

} elseif ($action == "ban") {
    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE users SET status='停權' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

} elseif ($action == "unban") {
    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE users SET status='正常' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin.php");
exit();
?>