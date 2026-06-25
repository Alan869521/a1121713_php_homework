<?php
ob_start();
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once(__DIR__ . '/tcpdf/tcpdf.php');

$conn = new mysqli(
    "sql303.infinityfree.com",
    "if0_41808875",
    "N1Zv5a8p2pvw",
    "if0_41808875_sql",
    3306
);

if ($conn->connect_error) {
    die('資料庫連線失敗：' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

$userId = $_SESSION['user_id'];

$sql = "
    SELECT users.name, users.email, profiles.*
    FROM users
    LEFT JOIN profiles ON users.id = profiles.user_id
    WHERE users.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

$name = !empty($profile['name']) ? $profile['name'] : '';
$email = !empty($profile['email']) ? $profile['email'] : '';
$phone = !empty($profile['phone']) ? $profile['phone'] : '尚未填寫';
$gender = !empty($profile['gender']) ? $profile['gender'] : '尚未填寫';
$birthday = !empty($profile['birthday']) ? $profile['birthday'] : '尚未填寫';
$address = !empty($profile['address']) ? $profile['address'] : '尚未填寫';
$bio = !empty($profile['bio']) ? $profile['bio'] : '尚未填寫';

$avatar = !empty($profile['avatar'])
    ? __DIR__ . '/' . $profile['avatar']
    : __DIR__ . '/uploads/default.png';

function drawRow($pdf, $y, $label, $value)
{
    $x = 30;
    $labelWidth = 40;
    $valueWidth = 110;
    $height = 12;

    $pdf->SetDrawColor(220, 220, 220);

    $pdf->SetFillColor(247, 247, 255);
    $pdf->Rect($x, $y, $labelWidth, $height, 'DF');

    $pdf->SetFillColor(255, 255, 255);
    $pdf->Rect($x + $labelWidth, $y, $valueWidth, $height, 'DF');

    $pdf->SetTextColor(51, 51, 51);
    $pdf->SetFont('msungstdlight', '', 10);
    $pdf->SetXY($x, $y + 3);
    $pdf->Cell($labelWidth, 6, $label, 0, 0, 'C');

    $pdf->SetFont('msungstdlight', '', 10);
    $pdf->SetXY($x + $labelWidth + 4, $y + 3);
    $pdf->Cell($valueWidth - 8, 6, $value, 0, 0, 'L');
}

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(18, 18, 18);
$pdf->SetAutoPageBreak(true, 18);
$pdf->AddPage();
$pdf->SetFont('msungstdlight', '', 12);

/* 背景 */
$pdf->SetFillColor(245, 246, 251);
$pdf->Rect(0, 0, 210, 297, 'F');

/* 主卡片 */
$pdf->SetFillColor(255, 255, 255);
$pdf->RoundedRect(18, 18, 174, 245, 5, '1111', 'F');

/* 上方藍色標題 */
$pdf->SetFillColor(102, 126, 234);
$pdf->RoundedRect(18, 18, 174, 28, 5, '1111', 'F');

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('msungstdlight', '', 18);
$pdf->SetXY(18, 21);
$pdf->Cell(174, 9, '個人資料', 0, 0, 'C');

/* 家教媒合平台小框 */
$platformBoxWidth = 64;
$platformBoxX = (210 - $platformBoxWidth) / 2;

$pdf->SetFillColor(255, 255, 255);
$pdf->RoundedRect($platformBoxX, 35, $platformBoxWidth, 8, 4, '1111', 'F');

$pdf->SetTextColor(102, 126, 234);
$pdf->SetFont('msungstdlight', '', 8);
$pdf->SetXY($platformBoxX, 35.3);
$pdf->Cell($platformBoxWidth, 7, '家教媒合平台', 0, 0, 'C');

/* 大頭貼 */
if (file_exists($avatar)) {
    $pdf->Image($avatar, 84, 64, 42, 42, 'PNG');
}

/* 姓名 */
$pdf->SetTextColor(51, 51, 51);
$pdf->SetFont('msungstdlight', '', 16);
$pdf->SetXY(18, 111);
$pdf->Cell(174, 10, $name, 0, 0, 'C');

/* 個人資料標題 */
$pdf->SetFillColor(240, 242, 255);
$pdf->RoundedRect(30, 132, 150, 10, 2, '1111', 'F');

$pdf->SetTextColor(51, 51, 51);
$pdf->SetFont('msungstdlight', '', 13);
$pdf->SetXY(30, 133.5);
$pdf->Cell(150, 7, '個人資料', 0, 1, 'C');

/* 表格 */
$startY = 148;
drawRow($pdf, $startY, 'Email', $email);
drawRow($pdf, $startY + 12, '電話', $phone);
drawRow($pdf, $startY + 24, '性別', $gender);
drawRow($pdf, $startY + 36, '生日', $birthday);
drawRow($pdf, $startY + 48, '地址', $address);

/* 個人介紹標題 */
$pdf->SetFillColor(240, 242, 255);
$pdf->RoundedRect(30, 214, 150, 10, 2, '1111', 'F');

$pdf->SetTextColor(51, 51, 51);
$pdf->SetFont('msungstdlight', '', 13);
$pdf->SetXY(30, 215.5);
$pdf->Cell(150, 7, '個人介紹', 0, 1, 'C');

/* 個人介紹內容框 */
$pdf->SetFillColor(255, 255, 255);
$pdf->SetDrawColor(220, 220, 220);
$pdf->Rect(30, 229, 150, 26, 'DF');

$pdf->SetFont('msungstdlight', '', 10);
$pdf->SetTextColor(68, 68, 68);
$pdf->SetXY(34, 233);
$pdf->MultiCell(142, 6, $bio, 0, 'L');

$stmt->close();
$conn->close();

ob_end_clean();
$pdf->Output('profile.pdf', 'I');
exit();