<?php

session_start();

$sID="student";
$sPWD="123";
$tID="teacher";
$tPWD="456";
$aID="admin";
$aPWD="789";

$account="frank";
$password="713study";

if(isset($_POST["uAccount"]) && isset($_POST["uPassword"])){
    $uAccount=$_POST["uAccount"];
    $uPassword=$_POST["uPassword"];

   if($uAccount==$sID && $uPassword==$sPWD){
        $_SESSION['login']="student";
        setcookie("uID",$uAccount,time()+3600);
        header("Location:camp.php");
   }elseif($uAccount==$tID && $uPassword==$tPWD){
        $_SESSION['login']="teacher";
        setcookie("uID",$uAccount,time()+3600);
        header("Location:teacher.php");
   }else if($uAccount==$aID && $uPassword==$aPWD){
        $_SESSION['login']="admin";
        setcookie("uID",$uAccount,time()+3600);
        header("Location:admin.php");
   }else{
        echo "登入失敗";
        header("Refresh:2;url=login.php");
   }
}



?>