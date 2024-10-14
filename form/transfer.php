<?php

use common\FeatureUtil;
use common\Point;
use common\User;

include "../connect.inc.php";
require_once "../common/FeatureUtil.php";
require_once "../common/User.php";
require_once "../common/Point.php";

if (isset($_SESSION['user_id'])) {
    $point = new Point($conn, $_SESSION["user_id"]);
    $point->deleteExpiredPoints();
    $user = new User($conn, $_SESSION['user_id']);
}
?>
<!doctype html>
<html lang="th">
<head>
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/transfer.css">
    <link rel="stylesheet" href="../css/pointuse.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/Login.js"></script>
    <title>Transfer</title>
</head>
<body>
<?php
?>
<div class="container">
    <div class="navbar">
        <a href="../index.php">Home</a>
    </div>
    <h2>โอนเงิน</h2>
    <div class="user-info">
        <div class="profile">
            <div class="profile-image"
                 style="background-image: url('<?php echo "../" . $user->getProfile(); ?>');"></div>
        </div>
        <p class="user-name"><?php echo $user->getUsername(); ?></p>
    </div>
    <form name="transfer_money" method="POST" action="../action/check_transaction.php">
        <?php
        FeatureUtil::displayMessage('handle', $_GET['transfer-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['transfer-error'] ?? null);
        ?>
        <div class="balance">
            <p>ยอดเงินที่ทำการได้</p>
            <h1>฿ <?php echo number_format($user->getWalletBalance(), 2); ?></h1>
        </div>
        <div class="transfer-method">
            <label class="radio">
                <input type="radio" name="transfer_type" value="username" checked
                       onclick="updatePlaceholder('username')">
                <span class="name">โอนโดยชื่อ</span>
            </label>
            <label class="radio">
                <input type="radio" name="transfer_type" value="email" onclick="updatePlaceholder('email')">
                <span class="name">โอนโดยเมล</span>
            </label>
        </div>
        <?php if ($point->getPoints() < Config::$pointRequirement) : ?>
            <div class="point_used">
                ใช้แต้ม Pts. <?= $point->getPoints() ?>
                <input
                        id="point_used_checkbox"
                        class="point-checkbox"
                        name="point_used"
                        type="checkbox"
                        value="1"
                        disabled
                />
                <label for="point_used_checkbox" class="pointswitch-label">
                    <div class="point-inner"></div>
                    <span class="tooltip">แต้มไม่เพียงพอ</span>
                </label>
            </div>
        <?php else: ?>
            <div class="point_used">
                ใช้แต้ม Pts. <?= $point->getPoints() ?>
                <input
                        id="point_used_checkbox"
                        class="point-checkbox"
                        name="point_used"
                        type="checkbox"
                        value="1"
                />
                <label for="point_used_checkbox" class="pointswitch-label">
                    <div class="point-inner"></div>
                    <span class="tooltip">ใช้แต้ม Pts. ในการลด ค่าธรรมเนียม 1 หน่วย ต่อ 1000 Pts.</span>
                </label>
            </div>
        <?php endif; ?>
        <input type="text" id="receiver" name="receiver" placeholder="Receiver Username" required><br>
        <script>
            function updatePlaceholder(type) {
                const receiverInput = document.getElementById('receiver');
                if (type === 'username')
                    receiverInput.placeholder = "ชื่อ ผู้รับ";
                 else if (type === 'email')
                    receiverInput.placeholder = "อีเมล ผู้รับ";
            }
        </script>
        <input type="number" name="amount" placeholder="จำนวนเงิน" required min="1" step="0.01"><br>
        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="2" name="transaction_type_id">
        <input type="submit" value="โอนเงิน" class="btn-send">
    </form>
</div>
</body>
</html>
