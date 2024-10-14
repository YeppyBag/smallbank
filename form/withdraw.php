<?php

use common\FeatureUtil;
use common\Point;
use common\User;

require_once "../common/FeatureUtil.php";
require_once "../common/User.php";
require_once "../common/Point.php";
include "../connect.inc.php";

if (isset($_SESSION['user_id'])) {
    $point = new Point($conn, $_SESSION["user_id"]);
    $point->deleteExpiredPoints();
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/deposit.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/Login.js"></script>
    <script src="../script/ConfirmAction.js"></script>
    <title>Withdraw</title>
</head>
<body>
<div class="container">
    <div class="navbar">
        <a href="../index.php">Home</a>
    </div>
    <h2>ถอนเงิน</h2>
    <form name="withdraw_money" method="post" action="../action/money_transaction.php"
          onsubmit="confirmAction(event, 'Withdraw')">
        <?php
        $user_id = $_SESSION['user_id'];
        FeatureUtil::displayMessage('handle', $_GET['withdraw-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['withdraw-error'] ?? null);
        $user = new User($conn, $user_id);
        ?>
        <div class="profile">
            <div class="profile-image"
                 style="background-image: url('<?php echo "../" . $user->getProfile(); ?>');"></div>
        </div>
        <div class="balance">
            <p>ยอดเงินที่ทำการได้</p>
            <h1>฿ <?php echo number_format($user->getWalletBalance(), 2); ?></h1>
        </div>
        <input type="number" name="amount" placeholder="จำนวนที่ต้องการถอน" required min="1" max = "<?php echo $user->getWalletBalance();?>" step="0.01">
        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="4" name="transaction_type_id">
        <input type="submit" value="ถอน">
    </form>
</div>
</body>
</html>
