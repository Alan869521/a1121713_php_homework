<?php
session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] != "admin"){
    header("Refresh:3; url=login.php");
    exit();
}
?>
<html>
<head><title>系統管理員</title></head>
<body style="background-color: lightgray;">
    <h1>⚙️ 網站後台管理系統</h1>
    <p>管理者：<?php echo $_SESSION['login']; ?>，您擁有最高修改權限。</p>
    <table border="1">
        <tr><th>功能</th><th>說明</th></tr>
        <tr><td>權限管理</td><td>新增或刪除帳號</td></tr>
        <tr><td>報名統計</td><td>查看各梯次報名人數</td></tr>
    </table>
    <br/>
    <a href="logout.php">登出系統</a>
    (回到登入頁面刪除cookie)
</body>
</html>