<head>
    <title>報名資料確認</title>
</head>

<body style="background-color:#fdf5e6;">
<?php

    $name=$_POST["uName"];
    $phone=$_POST["uPhone"];
    $birthday=$_POST["uBirthday"];
    $email=$_POST["uEmail"];
    $gender=$_POST["uGender"];
    $expect=$_POST["uExpect"];

    $trans=[
        "choose" => "未選擇",
        "insect" => "昆蟲動物",
        "baking" => "烘焙甜點",
        "basketball" => "籃球",
        "coding2" => "程式賽車",
        "bake" => "烘焙",
        "sports" => "運動",
        "animal" => "動物",
        "coding1" => "程式設計"
    ];

    $interestTEXT="無";
    if(isset($_POST["uInterest"])){
        $interestChinese=[];
        foreach($_POST["uInterest"] as $eng){
            $interestChinese[]=$trans[$eng] ?? $eng;
        }
        $interestTEXT=implode(", ", $interestChinese);
    }
    
    $first=$trans[$_POST["uFirstPriority"]];
    $second=$trans[$_POST["uSecondPriority"]];
    $amount=$_POST["uAmount"] ?? "1";
    $color=$_POST["uFavColor"] ?? "未選擇";
    $comment=$_POST["uComment"] ?? "無";



    echo "<center>";
    echo "<h1>報名資料確認</h1>";
    echo "<fieldset style='width:30%; background-color:white;padding:20px'>";
    echo "<legend><b>基本資料</b></legend>";

    echo "<div style='text-align:left'>";
    echo "<b>小朋友姓名:</b>&nbsp&nbsp".$name."<br/>";
    echo "<b>性別:</b>&nbsp&nbsp".($gender=="male" ? "男" : "女"). "<br/>";
    echo "<b>小朋友生日:</b>&nbsp&nbsp".$birthday."<br/>";
    echo "<b>聯絡人電話:</b>&nbsp&nbsp". $phone ."<br/>";
    echo "<b>聯絡人電子信箱:</b>&nbsp&nbsp". $email . "<br/>";
    echo "<b>報名人數:</b>&nbsp&nbsp".$amount."人<br/>";
    echo "</div>";
    echo "</fieldset>";
    echo "</center>";

    echo "<br/>";

    echo "<center>";
    echo "<fieldset style='width:30%; background-color:white; padding:20px'>";
    echo "<legend><b>課程偏好</b></legend>";
    echo "<div style='text-align:left'>";
    echo "<b>小朋友興趣愛好:</b>&nbsp&nbsp". $interestTEXT . "<br/>";
    echo "<b>第一志願:</b>&nbsp&nbsp".$first."<br/>";
    echo "<b>第二志願:</b>&nbsp&nbsp".$second."<br/>";
    echo "<b>小朋友喜歡的營隊代表色:</b>&nbsp&nbsp <span style='color:".$color."'>■</span> <br/>";
    echo "</div>";
    echo "</fieldset>";
    echo "</center>";
    echo "<br/>";

    echo "<center>";
    echo "<fieldset style='width:30%; background:white; padding:20px'>";
    echo "<legend><b>其他備註內容</b></legend>";
    echo "<div style='text-align:left'>";
    echo strip_tags(nl2br($comment));
    echo "</div>";
    echo "</fieldset>";
    echo "</center>";
    echo "<br/><br/>";

    

    echo "<center>";
    if($expect>=8){
        echo "看來您非常期待這次營隊!我們將會好好準備的!<br/>";
    }else{
        echo "看來您對這次營隊的期待程度不高呢!我們會努力讓您滿意的!<br/>";
    }
    echo "</center>";
    echo "<br/><br/>";

    echo "<center>";
    echo "<a href='javascript:history.back()'>回到上一頁修改</a>";
    echo "</center>";
?>
</body>


