<html>
<head><title>商品目錄</title></head>
<body style="background-color:#f0f8ff;">
    <h1>🛒 夏令營裝備選購</h1>
    <form action="savecart.php" method="post">
        選擇商品：
        <select name="Item">
            <option value="S001">S001 - 營隊制服 ($500)</option>
            <option value="S002">S002 - 運動毛巾 ($150)</option>
            <option value="S003">S003 - 紀念水壺 ($300)</option>
        </select>
        數量：<input type="number" name="Quantity" value="1" min="1" style="width:50px;">
        <input type="submit" value="加入購物車">
    </form>
    <a href="shoppingcart.php">查看我的購物車</a>
</body>
</html>