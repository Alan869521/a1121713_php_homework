<?php
session_start();

if (isset($_POST["Item"]) && isset($_POST["Quantity"])) {
    $id = $_POST["Item"];      
    $qty = $_POST["Quantity"]; 
    
    $items = array(
        "S001" => array("name" => "營隊制服", "price" => 500),
        "S002" => array("name" => "運動毛巾", "price" => 150),
        "S003" => array("name" => "紀念水壺", "price" => 300)
    );

    $name = $items[$id]["name"];
    $price = $items[$id]["price"];

    $index = 0;
    if (isset($_COOKIE["Cart"])) {
        $index = count($_COOKIE["Cart"]);
    }

    setcookie("Cart[$index][id]", $id, time() + 120);
    setcookie("Cart[$index][name]", $name, time() + 120);
    setcookie("Cart[$index][price]", $price, time() + 120);
    setcookie("Cart[$index][qty]", $qty, time() + 120);
}

header("Location: shoppingcart.php");
?>