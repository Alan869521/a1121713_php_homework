<?php
session_start();

// 假設登入成功後有存這些
// $_SESSION['user_id']
// $_SESSION['name']
// $_SESSION['role']

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 角色判斷
$role = $_SESSION['role'];

if ($role == 'admin') {
    header("Location: admin.php");
    exit();
} else {
    header("Location: user.php");
    exit();
}
?>