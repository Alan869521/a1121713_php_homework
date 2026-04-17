<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] != "teacher"){
    header("Refresh:3 ; url=login.php");
    exit();
}
?>

<html>
<head><title>教師專區</title></head>
<body style="background-color: lightblue;">
    <h1>😺 曜陽夏令營 - 教師教學系統</h1>
    <p>歡迎教師：<?php echo $_SESSION['login']; ?>。您可以在此查看課程進度。</p>
    <ul>
        <li>查看學生名單</li>
        <li>下載課程教材</li>
    </ul>
    <a href="logout.php">登出系統</a>
    (回到登入頁面刪除cookie)
</body>
</html>