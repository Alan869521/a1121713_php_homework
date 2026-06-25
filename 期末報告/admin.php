<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
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

$tutors = $conn->query("SELECT * FROM tutors ORDER BY id DESC");
$requests = $conn->query("SELECT * FROM tutor_requests ORDER BY id DESC");
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
$subjects = $conn->query("SELECT * FROM subjects ORDER BY id DESC");
$announcement_result = $conn->query("SELECT * FROM announcements ORDER BY id DESC");

$admin_count = 0;
$user_count = 0;

$role_result = $conn->query("
    SELECT role, COUNT(*) AS total
    FROM users
    GROUP BY role
");

while($row = $role_result->fetch_assoc()){

    if($row['role'] == 'admin'){
        $admin_count = $row['total'];
    }

    if($row['role'] == 'user'){
        $user_count = $row['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>管理者後台</title>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
google.charts.load('current', {'packages':['corechart']});

google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    var data = google.visualization.arrayToDataTable([
        ['身份', '人數'],
        ['管理者', <?php echo $admin_count; ?>],
        ['使用者', <?php echo $user_count; ?>]
    ]);

    var options = {
        title: '會員身分統計',
        pieHole: 0.4,
        width: 600,
        height: 350,
        legend: {
            position: 'bottom'
        }
    };

    var chart = new google.visualization.PieChart(
        document.getElementById('role_chart')
    );

    chart.draw(data, options);
}
</script>

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
}

.navbar a {
    padding: 10px 16px;
    background: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 10px;
}

.container {
    max-width: 1150px;
    margin: 45px auto;
    padding: 0 20px;
}

.title-card {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 35px;
    border-radius: 20px;
    margin-bottom: 30px;
}

.section {
    background: white;
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.1);
}

.item-card {
    background: #f7f7ff;
    border: 1px solid #ddd;
    padding: 22px;
    border-radius: 15px;
    margin-top: 18px;
}

.item-card p {
    margin: 8px 0;
    line-height: 1.6;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

input,
select,
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-sizing: border-box;
    margin-top: 8px;
}

textarea {
    height: 110px;
    resize: none;
}

.btn,
button {
    display: inline-block;
    margin-top: 12px;
    margin-right: 8px;
    padding: 9px 14px;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    border: none;
    cursor: pointer;
}

.approve {
    background: #28a745;
}

.reject {
    background: #f0ad4e;
}

.delete {
    background: #dc3545;
}

.edit {
    background: #667eea;
}

.ban {
    background: #6c757d;
}

.status {
    font-weight: bold;
}

.chart-section{
    background: white;
    padding: 30px;
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.1);
    text-align: center;
}

.chart-box{
    display:flex;
    justify-content:center;
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">📚 管理者後台</div>
    <a href="logout.php">登出</a>
</div>

<div class="container">

    <div class="title-card">
        <h1>管理者後台</h1>
        <p>管理公告、使用者、科目、家教註冊審核與徵求家教審核。</p>
    </div>

    <div class="chart-section">

    <h2>📊 會員統計</h2>

    <p>
        管理者：<?php echo $admin_count; ?> 人　
        使用者：<?php echo $user_count; ?> 人
    </p>

    <div class="chart-box">
        <div id="role_chart"></div>
    </div>

</div>

    <!-- 公告管理 -->
    <div class="section">
        <h2>📢 公告管理</h2>

        <h3>新增公告</h3>
        <form action="announcement_action.php" method="POST">
            <input type="hidden" name="action" value="add">

            <input type="text" name="title" placeholder="公告標題" required>
            <textarea name="content" placeholder="公告內容" required></textarea>

            <button class="edit" type="submit">新增公告</button>
        </form>

        <h3>所有公告</h3>

        <?php if ($announcement_result && $announcement_result->num_rows > 0): ?>
            <?php while ($row = $announcement_result->fetch_assoc()): ?>
                <div class="item-card">
                    <form action="announcement_action.php" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                        <textarea name="content" required><?php echo htmlspecialchars($row['content']); ?></textarea>

                        <button class="edit" type="submit">修改公告</button>

                        <a class="btn delete"
                           href="announcement_action.php?action=delete&id=<?php echo $row['id']; ?>"
                           onclick="return confirm('確定要刪除公告嗎？')">刪除</a>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>目前沒有公告。</p>
        <?php endif; ?>
    </div>

    <!-- 使用者管理 -->
    <div class="section">
        <h2>👤 使用者管理</h2>

        <h3>新增使用者</h3>
        <form action="admin_user_action.php" method="POST">
            <input type="hidden" name="action" value="add">

            <div class="form-grid">
                <input type="text" name="name" placeholder="姓名" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="password" placeholder="密碼" required>

                <select name="role" required>
                    <option value="user">user</option>
                    <option value="admin">admin</option>
                </select>
            </div>

            <button class="edit" type="submit">新增使用者</button>
        </form>

        <h3>所有使用者</h3>

        <?php if ($users && $users->num_rows > 0): ?>
            <?php while ($row = $users->fetch_assoc()): ?>
                <div class="item-card">
                    <form action="admin_user_action.php" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <div class="form-grid">
                            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>

                            <select name="role" required>
                                <option value="user" <?php if ($row['role'] == 'user') echo 'selected'; ?>>user</option>
                                <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>admin</option>
                            </select>

                            <input type="text" value="狀態：<?php echo htmlspecialchars($row['status'] ?? '正常'); ?>" readonly>
                        </div>

                        <button class="edit" type="submit">修改</button>

                        <a class="btn ban" href="admin_user_action.php?action=ban&id=<?php echo $row['id']; ?>">停權</a>
                        <a class="btn approve" href="admin_user_action.php?action=unban&id=<?php echo $row['id']; ?>">恢復</a>
                        <a class="btn delete"
                           href="admin_user_action.php?action=delete&id=<?php echo $row['id']; ?>"
                           onclick="return confirm('確定要刪除此使用者嗎？')">刪除</a>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>目前沒有使用者資料。</p>
        <?php endif; ?>
    </div>

    <!-- 科目管理 -->
    <div class="section">
        <h2>📘 科目管理</h2>

        <h3>新增科目</h3>
        <form action="admin_subject_action.php" method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="subject_name" placeholder="請輸入科目名稱" required>
            <button class="edit" type="submit">新增科目</button>
        </form>

        <h3>所有科目</h3>

        <?php if ($subjects && $subjects->num_rows > 0): ?>
            <?php while ($row = $subjects->fetch_assoc()): ?>
                <div class="item-card">
                    <form action="admin_subject_action.php" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                        <input type="text" name="subject_name" value="<?php echo htmlspecialchars($row['subject_name']); ?>" required>

                        <button class="edit" type="submit">修改科目</button>

                        <a class="btn delete"
                           href="admin_subject_action.php?action=delete&id=<?php echo $row['id']; ?>"
                           onclick="return confirm('確定要刪除此科目嗎？')">刪除</a>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>目前沒有科目資料。</p>
        <?php endif; ?>
    </div>

    <!-- 家教註冊審核 -->
    <div class="section">
        <h2>📝 家教註冊審核</h2>

        <?php if ($tutors && $tutors->num_rows > 0): ?>
            <?php while ($row = $tutors->fetch_assoc()): ?>
                <div class="item-card">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><strong>地區：</strong><?php echo htmlspecialchars($row['area']); ?></p>
                    <p><strong>對象：</strong><?php echo htmlspecialchars($row['target']); ?></p>
                    <p><strong>科目：</strong><?php echo htmlspecialchars($row['subject']); ?></p>
                    <p><strong>時薪：</strong><?php echo htmlspecialchars($row['hourly_rate']); ?> 元</p>
                    <p><strong>線上：</strong><?php echo htmlspecialchars($row['online']); ?></p>
                    <p><strong>介紹：</strong><?php echo nl2br(htmlspecialchars($row['intro'])); ?></p>
                    <p class="status"><strong>狀態：</strong><?php echo htmlspecialchars($row['status'] ?? '待審核'); ?></p>

                    <a class="btn approve" href="review_action.php?type=tutor&id=<?php echo $row['id']; ?>&action=approve">通過</a>
                    <a class="btn reject" href="review_action.php?type=tutor&id=<?php echo $row['id']; ?>&action=reject">拒絕</a>
                    <a class="btn delete"
                       href="review_action.php?type=tutor&id=<?php echo $row['id']; ?>&action=delete"
                       onclick="return confirm('確定要刪除嗎？')">刪除</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>目前沒有家教註冊資料。</p>
        <?php endif; ?>
    </div>

    <!-- 徵求家教審核 -->
    <div class="section">
        <h2>📢 徵求家教審核</h2>

        <?php if ($requests && $requests->num_rows > 0): ?>
            <?php while ($row = $requests->fetch_assoc()): ?>
                <div class="item-card">
                    <h3><?php echo htmlspecialchars($row['subject']); ?></h3>
                    <p><strong>地區：</strong><?php echo htmlspecialchars($row['area']); ?></p>
                    <p><strong>對象：</strong><?php echo htmlspecialchars($row['target']); ?></p>
                    <p><strong>時薪：</strong><?php echo htmlspecialchars($row['hourly_rate']); ?> 元</p>
                    <p><strong>線上：</strong><?php echo htmlspecialchars($row['online']); ?></p>
                    <p><strong>需求說明：</strong><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                    <p class="status"><strong>狀態：</strong><?php echo htmlspecialchars($row['status'] ?? '待審核'); ?></p>

                    <a class="btn approve" href="review_action.php?type=request&id=<?php echo $row['id']; ?>&action=approve">通過</a>
                    <a class="btn reject" href="review_action.php?type=request&id=<?php echo $row['id']; ?>&action=reject">拒絕</a>
                    <a class="btn delete"
                       href="review_action.php?type=request&id=<?php echo $row['id']; ?>&action=delete"
                       onclick="return confirm('確定要刪除嗎？')">刪除</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>目前沒有徵求家教資料。</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>