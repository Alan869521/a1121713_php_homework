<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'user') {
    header("Location: indexcheck.php");
    exit();
}

$conn = new mysqli(
    "sql303.infinityfree.com",
    "if0_41808875",
    "N1Zv5a8p2pvw",
    "if0_41808875_sql",
    3306
);

if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$announcements = $conn->query("SELECT * FROM announcements ORDER BY id DESC");

$tutor_result = $conn->query("
    SELECT * FROM tutors
    WHERE status = '已通過'
    ORDER BY id DESC
");

$student_result = $conn->query("
    SELECT * FROM tutor_requests
    WHERE status = '已通過'
    ORDER BY id DESC
");

function subjectOptions($conn) {
    $subjects = $conn->query("SELECT * FROM subjects ORDER BY subject_name ASC");

    if ($subjects && $subjects->num_rows > 0) {
        while ($subject = $subjects->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($subject['subject_name']) . '">';
            echo htmlspecialchars($subject['subject_name']);
            echo '</option>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>使用者首頁</title>

<style>
body {
    margin: 0;
    font-family: Arial, "Microsoft JhengHei", sans-serif;
    background: #f4f6fb;
}

.navbar {
    background: white;
    padding: 18px 50px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 5px 18px rgba(0,0,0,0.12);
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.nav-buttons button,
.logout {
    margin-left: 10px;
    padding: 10px 16px;
    border: none;
    border-radius: 10px;
    background: #667eea;
    color: white;
    cursor: pointer;
    text-decoration: none;
    font-size: 14px;
}

.nav-buttons button:hover,
.logout:hover {
    opacity: 0.85;
}

.announcement-wrapper {
    max-width: 1100px;
    margin: 25px auto 0;
    padding: 0 20px;
}

.announcement-card {
    background: #fff4d6;
    border-left: 6px solid #ffb300;
    padding: 18px 22px;
    border-radius: 14px;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.announcement-card h3 {
    margin-top: 0;
    color: #d17b00;
}

.announcement-card p {
    margin: 10px 0;
    color: #444;
    line-height: 1.7;
}

.announcement-card small {
    color: #777;
}

.container {
    max-width: 1100px;
    margin: 45px auto;
    padding: 0 20px;
}

.welcome {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 35px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card {
    display: none;
    background: white;
    padding: 35px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.card.active {
    display: block;
}

h2 {
    margin-top: 0;
    color: #333;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.form-group {
    text-align: left;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #444;
}

input,
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
    box-sizing: border-box;
}

textarea {
    height: 120px;
    resize: none;
}

.full {
    grid-column: span 2;
}

.submit-btn {
    margin-top: 25px;
    padding: 13px 28px;
    border: none;
    border-radius: 10px;
    background: #667eea;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.submit-btn:hover {
    opacity: 0.9;
}

.result-title {
    margin-top: 35px;
    padding-top: 25px;
    border-top: 1px solid #ddd;
}

.result-card {
    background: #f7f7ff;
    padding: 22px;
    border-radius: 15px;
    margin-top: 18px;
    border: 1px solid #ddd;
}

.result-card h3 {
    margin-top: 0;
    color: #333;
}

.result-card p {
    margin: 8px 0;
    color: #444;
    line-height: 1.6;
}

.detail-btn {
    display: inline-block;
    margin-top: 12px;
    margin-right: 8px;
    padding: 10px 16px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
}

.detail-btn:hover {
    opacity: 0.85;
}

.empty {
    margin-top: 18px;
    background: #f7f7ff;
    padding: 22px;
    border-radius: 15px;
    color: #777;
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        gap: 15px;
    }

    .nav-buttons {
        text-align: center;
    }

    .nav-buttons button,
    .logout {
        margin-top: 8px;
        display: inline-block;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .full {
        grid-column: span 1;
    }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">📚 家教媒合平台</div>

    <div class="nav-buttons">
        <button onclick="showSection('findTutor')">尋找家教</button>
        <button onclick="showSection('findStudent')">尋找學生</button>
        <button onclick="showSection('tutorRegister')">家教註冊</button>
        <button onclick="showSection('requestTutor')">徵求家教</button>
        <a href="my_applications.php" class="logout">我的應徵紀錄</a>
        <a href="received_applications.php" class="logout">收到的應徵</a>
        <a href="my_favorites.php" class="logout">我的收藏</a>
        <a href="profile.php" class="logout">個人資料</a>
        <a href="logout.php" class="logout">登出</a>
    </div>
</div>

<div class="announcement-wrapper">
    <?php if ($announcements && $announcements->num_rows > 0): ?>
        <?php while ($announcement = $announcements->fetch_assoc()): ?>
            <div class="announcement-card">
                <h3>📢 <?php echo htmlspecialchars($announcement['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                <small>發布時間：<?php echo htmlspecialchars($announcement['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="announcement-card">
            目前沒有公告
        </div>
    <?php endif; ?>
</div>

<div class="container">

    <div class="welcome">
        <h1>歡迎，<?php echo htmlspecialchars($_SESSION['name']); ?>！</h1>
        <p>你可以在這裡尋找家教、尋找學生、註冊成為家教，或發布徵求家教需求。</p>
    </div>

    <div id="findTutor" class="card active">
        <h2>🔍 尋找家教</h2>

        <form action="search_tutor.php" method="GET">
            <div class="form-grid">

                <div class="form-group">
                    <label>地區</label>
                    <select name="area">
                        <option value="">不限地區</option>
                        <option value="台北市">台北市</option>
                        <option value="新北市">新北市</option>
                        <option value="桃園市">桃園市</option>
                        <option value="台中市">台中市</option>
                        <option value="台南市">台南市</option>
                        <option value="高雄市">高雄市</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>家教對象</label>
                    <select name="target">
                        <option value="">不限對象</option>
                        <option value="國小">國小</option>
                        <option value="國中">國中</option>
                        <option value="高中">高中</option>
                        <option value="大學">大學</option>
                        <option value="成人">成人</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>科目</label>
                    <select name="subject">
                        <option value="">不限科目</option>
                        <?php subjectOptions($conn); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>最低時薪</label>
                    <input type="number" name="min_price" placeholder="例如：400">
                </div>

                <div class="form-group full">
                    <label>可不可線上</label>
                    <select name="online">
                        <option value="">不限</option>
                        <option value="可以">可以線上</option>
                        <option value="不可以">不可以線上</option>
                    </select>
                </div>

            </div>

            <button class="submit-btn" type="submit">開始搜尋家教</button>
        </form>

        <h2 class="result-title">全部家教</h2>

        <?php if ($tutor_result && $tutor_result->num_rows > 0): ?>
            <?php while ($row = $tutor_result->fetch_assoc()): ?>
                <div class="result-card">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><strong>教學地區：</strong><?php echo htmlspecialchars($row['area']); ?></p>
                    <p><strong>家教對象：</strong><?php echo htmlspecialchars($row['target']); ?></p>
                    <p><strong>教學科目：</strong><?php echo htmlspecialchars($row['subject']); ?></p>
                    <p><strong>最低時薪：</strong><?php echo htmlspecialchars($row['hourly_rate']); ?> 元</p>
                    <p><strong>可不可線上：</strong><?php echo htmlspecialchars($row['online']); ?></p>
                    <p><strong>自我介紹：</strong><?php echo nl2br(htmlspecialchars($row['intro'])); ?></p>

                    <a class="detail-btn" href="tutor_detail.php?id=<?php echo $row['id']; ?>">查看詳細資料</a>
                    <a class="detail-btn" href="favorite.php?tutor_id=<?php echo $row['id']; ?>">收藏</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">目前尚無家教資料。</div>
        <?php endif; ?>
    </div>

    <div id="findStudent" class="card">
        <h2>👨‍🎓 尋找學生</h2>

        <form action="search_student.php" method="GET">
            <div class="form-grid">

                <div class="form-group">
                    <label>地區</label>
                    <select name="area">
                        <option value="">不限地區</option>
                        <option value="台北市">台北市</option>
                        <option value="新北市">新北市</option>
                        <option value="桃園市">桃園市</option>
                        <option value="台中市">台中市</option>
                        <option value="台南市">台南市</option>
                        <option value="高雄市">高雄市</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>家教對象</label>
                    <select name="target">
                        <option value="">不限對象</option>
                        <option value="國小">國小</option>
                        <option value="國中">國中</option>
                        <option value="高中">高中</option>
                        <option value="大學">大學</option>
                        <option value="成人">成人</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>科目</label>
                    <select name="subject">
                        <option value="">不限科目</option>
                        <?php subjectOptions($conn); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>最低時薪</label>
                    <input type="number" name="min_price" placeholder="例如：400">
                </div>

                <div class="form-group full">
                    <label>可不可線上</label>
                    <select name="online">
                        <option value="">不限</option>
                        <option value="可以">可以線上</option>
                        <option value="不可以">不可以線上</option>
                    </select>
                </div>

            </div>

            <button class="submit-btn" type="submit">開始搜尋學生</button>
        </form>

        <h2 class="result-title">全部學生需求</h2>

        <?php if ($student_result && $student_result->num_rows > 0): ?>
            <?php while ($row = $student_result->fetch_assoc()): ?>
                <div class="result-card">
                    <h3><?php echo htmlspecialchars($row['subject']); ?></h3>
                    <p><strong>需求地區：</strong><?php echo htmlspecialchars($row['area']); ?></p>
                    <p><strong>家教對象：</strong><?php echo htmlspecialchars($row['target']); ?></p>
                    <p><strong>需求科目：</strong><?php echo htmlspecialchars($row['subject']); ?></p>
                    <p><strong>可接受時薪：</strong><?php echo htmlspecialchars($row['hourly_rate']); ?> 元</p>
                    <p><strong>可不可線上：</strong><?php echo htmlspecialchars($row['online']); ?></p>
                    <p><strong>詳細需求：</strong><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

                    <a class="detail-btn" href="apply.php?id=<?php echo $row['id']; ?>">我要應徵</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">目前尚無學生需求。</div>
        <?php endif; ?>
    </div>

    <div id="tutorRegister" class="card">
        <h2>📝 家教註冊</h2>

        <form action="tutor_register.php" method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>姓名</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>教學地區</label>
                    <input type="text" name="area" placeholder="例如：台中市西屯區" required>
                </div>

                <div class="form-group">
                    <label>可教對象</label>
                    <select name="target" required>
                        <option value="國小">國小</option>
                        <option value="國中">國中</option>
                        <option value="高中">高中</option>
                        <option value="大學">大學</option>
                        <option value="成人">成人</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>可教科目</label>
                    <select name="subject" required>
                        <option value="">請選擇科目</option>
                        <?php subjectOptions($conn); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>希望最低時薪</label>
                    <input type="number" name="hourly_rate" placeholder="例如：500" required>
                </div>

                <div class="form-group">
                    <label>可不可線上</label>
                    <select name="online" required>
                        <option value="可以">可以</option>
                        <option value="不可以">不可以</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>自我介紹</label>
                    <textarea name="intro" placeholder="請簡單介紹你的教學經驗、特色或證照"></textarea>
                </div>

            </div>

            <button class="submit-btn" type="submit">送出家教註冊</button>
        </form>
    </div>

    <div id="requestTutor" class="card">
        <h2>📢 徵求家教</h2>

        <form action="request_tutor.php" method="POST">
            <div class="form-grid">

                <div class="form-group">
                    <label>需求地區</label>
                    <input type="text" name="area" placeholder="例如：高雄市三民區" required>
                </div>

                <div class="form-group">
                    <label>家教對象</label>
                    <select name="target" required>
                        <option value="國小">國小</option>
                        <option value="國中">國中</option>
                        <option value="高中">高中</option>
                        <option value="大學">大學</option>
                        <option value="成人">成人</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>需求科目</label>
                    <select name="subject" required>
                        <option value="">請選擇科目</option>
                        <?php subjectOptions($conn); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>可接受最低時薪</label>
                    <input type="number" name="hourly_rate" placeholder="例如：400" required>
                </div>

                <div class="form-group">
                    <label>可不可線上</label>
                    <select name="online" required>
                        <option value="可以">可以</option>
                        <option value="不可以">不可以</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>詳細需求</label>
                    <textarea name="description" placeholder="請說明上課時間、學生程度、希望老師條件等"></textarea>
                </div>

            </div>

            <button class="submit-btn" type="submit">發布徵求家教</button>
        </form>
    </div>

</div>

<script>
function showSection(sectionId) {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        card.classList.remove('active');
    });

    document.getElementById(sectionId).classList.add('active');
}
</script>

</body>
</html>