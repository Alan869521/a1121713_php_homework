<?php
if (isset($_GET["id"])) {
    $index = $_GET["id"];
    
    setcookie("Cart[$index][id]", "", time() - 120);
    setcookie("Cart[$index][name]", "", time() - 120);
    setcookie("Cart[$index][price]", "", time() - 120);
    setcookie("Cart[$index][qty]", "", time() - 120);
}

header("Location: shoppingcart.php");
?>