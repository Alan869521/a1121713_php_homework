<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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

$user_id = $_SESSION['user_id'];

$action = $_GET['action'] ?? $_POST['action'] ?? '';

/* =========================
   上傳大頭貼
========================= */

function uploadAvatar($user_id) {

    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] != 0) {
        return null;
    }

    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = $_FILES['avatar']['name'];
    $tmpName = $_FILES['avatar']['tmp_name'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($fileExt, $allowExt)) {
        die("只允許 jpg、jpeg、png、gif、webp 圖片");
    }

    /* =========================
       讀取原圖
    ========================= */

    switch ($fileExt) {

        case 'jpg':
        case 'jpeg':
            $source = imagecreatefromjpeg($tmpName);
            break;

        case 'png':
            $source = imagecreatefrompng($tmpName);
            break;

        case 'gif':
            $source = imagecreatefromgif($tmpName);
            break;

        case 'webp':
            $source = imagecreatefromwebp($tmpName);
            break;

        default:
            die("圖片格式不支援");
    }

    if (!$source) {
        die("圖片讀取失敗");
    }

    /* =========================
       原圖尺寸
    ========================= */

    $srcWidth = imagesx($source);
    $srcHeight = imagesy($source);

    /* =========================
       建立 300x300 新圖
    ========================= */

    $newSize = 300;

    $newImage = imagecreatetruecolor($newSize, $newSize);

    /* =========================
       開啟透明背景
    ========================= */

    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);

    $transparent = imagecolorallocatealpha(
        $newImage,
        0,
        0,
        0,
        127
    );

    imagefill(
        $newImage,
        0,
        0,
        $transparent
    );

    /* =========================
       等比例縮放
    ========================= */

    $scale = min(
        $newSize / $srcWidth,
        $newSize / $srcHeight
    );

    $resizeWidth = intval($srcWidth * $scale);
    $resizeHeight = intval($srcHeight * $scale);

    /* =========================
       置中
    ========================= */

    $x = intval(($newSize - $resizeWidth) / 2);
    $y = intval(($newSize - $resizeHeight) / 2);

    /* =========================
       壓縮圖片
    ========================= */

    imagecopyresampled(
        $newImage,
        $source,
        $x,
        $y,
        0,
        0,
        $resizeWidth,
        $resizeHeight,
        $srcWidth,
        $srcHeight
    );

    /* =========================
       存成 PNG
    ========================= */

    $newFileName =
        "avatar_" .
        $user_id .
        "_" .
        time() .
        ".png";

    $savePath = $uploadDir . $newFileName;

    imagepng($newImage, $savePath);

    /* =========================
       釋放記憶體
    ========================= */

    imagedestroy($source);
    imagedestroy($newImage);

    return $savePath;
}

/* =========================
   新增個人資料
========================= */

if ($action == "add") {

    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $bio = $_POST['bio'];

    $avatar = uploadAvatar($user_id);

    $sql = "
    INSERT INTO profiles
    (
        user_id,
        phone,
        address,
        gender,
        birthday,
        bio,
        avatar
    )
    VALUES
    (?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "issssss",
        $user_id,
        $phone,
        $address,
        $gender,
        $birthday,
        $bio,
        $avatar
    );

    $stmt->execute();
}

/* =========================
   修改個人資料
========================= */

elseif ($action == "update") {

    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $bio = $_POST['bio'];

    $avatar = uploadAvatar($user_id);

    if ($avatar) {

        $sql = "
        UPDATE profiles
        SET
            phone=?,
            address=?,
            gender=?,
            birthday=?,
            bio=?,
            avatar=?
        WHERE user_id=?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssssi",
            $phone,
            $address,
            $gender,
            $birthday,
            $bio,
            $avatar,
            $user_id
        );

    } else {

        $sql = "
        UPDATE profiles
        SET
            phone=?,
            address=?,
            gender=?,
            birthday=?,
            bio=?
        WHERE user_id=?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssi",
            $phone,
            $address,
            $gender,
            $birthday,
            $bio,
            $user_id
        );
    }

    $stmt->execute();
}

/* =========================
   刪除個人資料
========================= */

elseif ($action == "delete") {

    /* 刪除圖片 */

    $check = $conn->prepare("
        SELECT avatar
        FROM profiles
        WHERE user_id=?
    ");

    $check->bind_param("i", $user_id);
    $check->execute();

    $result = $check->get_result();
    $row = $result->fetch_assoc();

    if (!empty($row['avatar'])) {

        if (
            file_exists($row['avatar']) &&
            $row['avatar'] != 'uploads/default.png'
        ) {
            unlink($row['avatar']);
        }
    }

    /* 刪除資料 */

    $sql = "
    DELETE FROM profiles
    WHERE user_id=?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $user_id
    );

    $stmt->execute();
}

/* =========================
   回頁面
========================= */

header("Location: profile.php");
exit();
?>