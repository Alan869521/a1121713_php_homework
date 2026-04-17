<html>
<head><title>購物車內容</title></head>
<body>
    <h2>🛒 您的選購明細</h2>
    <table border="1" style="width:600px; border-collapse:collapse; text-align:center;">
        <tr bgcolor="pink">
            <th>商品代號</th>
            <th>商品名稱</th>
            <th>單價</th>
            <th>購買數量</th>
            <th>功能</th>
        </tr>
        <?php
        $total = 0;

        if (isset($_COOKIE["Cart"]) && is_array($_COOKIE["Cart"])) {
            foreach ($_COOKIE["Cart"] as $index => $data) {
                $total += ($data["price"] * $data["qty"]);

                echo "<tr>";
                echo "<td>" . $data["id"] . "</td>";   
                echo "<td>" . $data["name"] . "</td>";
                echo "<td>$" . $data["price"] . "</td>";
                echo "<td>" . $data["qty"] . "</td>";
                echo "<td><a href='delete.php?id=" . $index . "'>移除</a></td>";
                echo "</tr>";
            }
 
            echo "<tr bgcolor='pink'>";
            echo "<td colspan='3' align='right'><b>總計金額：</b></td>";
            echo "<td colspan='2'><b>NT$ " . $total . " 元</b></td>";
            echo "</tr>";
        } else {
            echo "<tr><td colspan='5'>購物車目前是空的 (或已過期)</td></tr>";
        }
        ?>
    </table>
    <br/>
    <a href="catalog.php">繼續選購</a>
</body>
</html>