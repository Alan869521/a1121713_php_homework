<?php
$account="frank";
$password="713study";

if(isset($_POST["uAccount"]) && isset($_POST["uPassword"])){
    $uAccount=$_POST["uAccount"];
    $uPassword=$_POST["uPassword"];

    if($account==$uAccount && $password==$uPassword){
        header("Location: camp.php");
    }else{
        echo "登入失敗";
        header("Refresh:2;url=login.php");
    }
}



?>