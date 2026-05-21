<?php require_once 'db_config.php'; ?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>寄信控制台</title></head>
<body>
    <h2>寄信系統控制台</h2>
    
    <form action="process_send.php" method="POST">
        <p>郵件主旨：<input type="text" name="subject" required></p>
        <p>郵件內容：<textarea name="content" required></textarea></p>
        
        <hr>
        
        <p>寄送對象：
            <select name="mode">
                <option value="all">全部寄送</option>
                <option value="random">隨機寄送</option>
            </select>
            隨機筆數：<input type="number" name="rand_num" value="1" min="1">
        </p>

        <p>發送間隔：<input type="number" name="seconds" value="2"> 秒</p>
        
        <button type="submit">開始大量寄信</button>
    </form>
</body>
</html>