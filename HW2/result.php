<?php

    $name=$_POST["uName"];
    $phone=$_POST["uPhone"];
    $email=$_POST["uEmail"];
    $gender=$_POST["uGender"];
    $expect=$_POST["uExpect"];

    $interests=isset($_POST["uInterest"]) ? implode(",",$_POST["uInterest"]) : "無";

    echo "<h1>報名資料確認</h1>";
    echo "小朋友姓名:".$name."<br/>";
    echo "性別:".($gender=="male" ? "男" : "女"). "<br/>";
    echo "聯絡電話:". $phone ."<br/>";
    echo "電子信箱:". $email . "<br/>";
    echo "興趣愛好:". $interests . "<br/><br/>";

    if($expect>=8){
        echo "看來您非常期待這次營隊!我們將會好好準備的!<br/>";
    }
?>

<br/>
<br/>

<a href="javascript:history.back()">回到上一頁修改</a>

