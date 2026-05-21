<?php
// 1. 引入必要檔案
require_once 'db_config.php';
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// 2. 接收從 send.php 傳過來的設定
$mode     = $_POST['mode'];
$rand_num = intval($_POST['rand_num']);
$subject  = $_POST['subject'];
$content  = $_POST['content'];
$seconds  = intval($_POST['seconds']);

// 3. 根據模式準備 SQL 指令
if ($mode == 'random') {
    $sql = "SELECT email FROM recipients ORDER BY RAND() LIMIT $rand_num";
} else {
    $sql = "SELECT email FROM recipients";
}

$result  = $conn->query($sql);
$targets = [];
while($row = $result->fetch_assoc()) {
    $targets[] = $row['email'];
}
$total = count($targets);

echo "<h2>📧 郵件發送任務開始</h2>";

// 4. 開始循環寄信
foreach ($targets as $index => $email) {
    $current_count = $index + 1;
    $percent = round(($current_count / $total) * 100);

    // 顯示進度與正在寄的人
    echo "<div><b>[{$percent}%]</b> 正在發送第 {$current_count} 封 (至: $email) ... ";

    // --- PHPMailer 寄信設定 ---
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'a1121713@mail.nuk.edu.tw'; 
        $mail->Password   = 'yxulezcdmqedrtpw';          
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('a1121713@mail.nuk.edu.tw', '垃圾郵件系統');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->send();
        echo "<span style='color:green;'>成功！</span></div>";
    } catch (Exception $e) {
        echo "<span style='color:red;'>失敗！ 錯誤: {$mail->ErrorInfo}</span></div>";
    }

    // 5. 強制瀏覽器顯示目前的進度
    ob_flush(); 
    flush();

    // 6. 設定間隔秒數 (如果是最後一封就不用停了)
    if ($current_count < $total) {
        sleep($seconds);
    }
}

echo "<h3>🏁 任務執行完畢，總共寄出 $total 封郵件。</h3>";
echo "<a href='send.php'>返回控制台</a>";
?>