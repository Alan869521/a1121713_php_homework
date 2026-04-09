<html>

<head>
    <title>曜陽兒童夏令營報名表單</title>
</head>

<body style="background-color:lightyellow;">
    <a name="top"></a>
    <h1>Shining!夏令營報名表單</h1>
    </br>

    <img src="https://d3gjxtgqyywct8.cloudfront.net/o2o/image/828276ab-11b0-4643-b4c7-11f7f41d4e6f.jpg" width="600" align="right" hspace="100">
    <br/>


    <font size="4" color="red"><b>早鳥優惠:即日起至5/31日以前，享有「六折」優惠</b></font>
    
    <p>
        歡迎參加,<i>😺「曜陽暑期營隊」😺</i>
        <br/>
        我們提供多元的課程活動，讓孩子們在暑假期間能夠學習新知識、培養興趣，並且結交新朋友。
        <br/>請填寫以下報名表單，<u>注意:請務必填寫正確的聯絡電話</u>，以便我們能夠順利聯繫您。
    </p>

    <h3>報名須知與準備物品</h3>
    <ul>
        <li>請自備水壺與毛巾及個人盥洗衣物。</li>
        <li>營隊期間提供午餐（葷/素請於備註說明）。</li>
        <li>若有特殊醫療史，請務必詳細填寫。</li>
        <li><del>原價:NT$5000</del> &nbsp;&nbsp;&nbsp;&nbsp; <b>直至五月前報名，即享有六折優惠!</b></li>
    </ul>

    <table border="1" bgcolor="#fff38b">
        <tr bgcolor="orange">
            <th>課程梯次</th>
            <th>活動主題</th>
            <th>活動費用</th>
        </tr>
        <tr>
            <td>第一梯次(7/01-7/05)</td>
            <td bgcolor="lightgreen">昆蟲大冒險</td>
            <td>NT$5,000</td>
        </tr>
        <tr>
            <td>第二梯次(7/08-7/12)</td>
            <td bgcolor="red">小小烘焙師</td>
            <td>NT$5,000</td>
        </tr>
        <tr>
            <td>第三梯次(7/15-7/19)</td>
            <td bgcolor="lightblue">灌籃高手籃球營</td> 
            <td>NT$4,800</td>
        </tr>
        <tr>
            <td>第四梯次(7/22-7/26)</td>
            <td bgcolor="pink">程式賽車手 (熱門!)</td> 
            <td>NT$5,500</td>
        </tr>
    </table>
    <br/>

    <p>
        想要看更多精采畫面嗎?<br/>
        <a href="https://www.sng.idv.tw/" target="_blank"><b>點我觀看更多詳情活動官網</b></a>
    </p>

    <img src="happy.png" width="300" align="right" hspace="70">
    <img src="Designer.png" width="300" align="right" hspace="70">

    <form action="result.php" method="post" style="line-height: 2.0">

        <fieldset style="width:30%;">
            <legend><b>基本資料</b></legend>
            小朋友姓名:<input type="text" name="uName" placeholder="請輸入姓名" required><br/>
            小朋友生日:<input type="date" name="uBirthday"><br/>
            聯絡人電話:<input type="text" name="uPhone" placeholder="09xx-xxx-xxx" required><br/>
            聯絡人信箱:<input type="email" name="uEmail" placeholder="example@gmail.com"><br/>
            報名確認碼:<input type="password" name="uPass"><br/>

            性別:
            <input type="radio" name="uGender" value="male" checked> 男
            <input type="radio" name="uGender" value="female"> 女
            <br/>

            小朋友興趣(可多選):
            <input type="checkbox" name="uInterest[]" value="bake">烘焙
            <input type="checkbox" name="uInterest[]" value="sports">運動
            <input type="checkbox" name="uInterest[]" value="animal">動物
            <input type="checkbox" name="uInterest[]" value="coding1">程式設計
            <br/>
        </fieldset>

        <fieldset style="width:30%;">
            <legend><b>較有興趣的課程活動:</b></legend>
            第一志願:
            <select name="uFirstPriority">
                <option value="choose">請選擇</option>
                <option value="insect">昆蟲動物</option>
                <option value="baking">烘焙甜點</option>
                <option value="basketball">籃球</option>
                <option value="coding2">程式賽車</option>
            </select>
            <br/>

            第二志願:
            <select name="uSecondPriority">
                <option value="choose">請選擇</option>
                <option value="insect">昆蟲動物</option>
                <option value="baking">烘焙甜點</option>
                <option value="basketball">籃球</option>
                <option value="coding">程式賽車</option>
            </select>
            <br/>

            對本次營隊的期待程度(1-10):
            <br/>
            1<input type="range" name="uExpect" min="1" max="10" value="10">10
            <br/>

            預計報名人數:
            <br/>
            <input type="number" name="uAmount" min="1" max="10" value="1">人
            <br/> 

            你最喜歡的營隊代表色:
            <br/>
            <input type="color" name="uFavColor" value="#FFA500">
            <br/>

        </fieldset>

        <br/>

        其他備註事項，如過敏史與飲食需求:<br/>
        <textarea name="uComment" rows="5" cols="50" placeholder="請在此輸入備註內容..."></textarea>
        <br/>
        <br/>

        <input type="hidden" name="uSource" value="FB_Ads">

        <center>
            <input type="submit" value="送出報名表單">
            <input type="reset" value="重新填寫">
            <input type="button" value="聯絡客服" onclick="alert('客服人員將與你聯繫')">
        </center>

    </form>

    <hr width="100%" color="gray"/>
    <center><a href="#top">回到頂部</a></center>

</body>

</html>