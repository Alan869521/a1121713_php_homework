<?php
$host = "localhost";    // 伺服器
$user = "root";         // 帳號
$pass = "";             // 密碼
$dbname = "spam_system"; // 資料庫名

// 建立連線
$conn = new mysqli($host, $user, $pass, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 設定編碼為 UTF8，避免中文亂碼
$conn->set_charset("utf8");
?>