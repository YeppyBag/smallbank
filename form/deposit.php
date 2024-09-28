<?php

use common\FeatureUtil;
use common\User;

require_once "../common/FeatureUtil.php";
require_once "../common/User.php";
include "../connect.inc.php";
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/deposit.css">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/Login.js"></script>
    <title>Deposit</title>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="../index.php">Home</a>
    </div>
    <h2>ฝากเงิน</h2>
    <form name="deposit_money" method="post" action="../action/money_transaction.php" onsubmit="confirmDeposit(event)">
        <?php
        $user_id = $_SESSION['user_id'];
        FeatureUtil::displayMessage('handle', $_GET['deposit-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['deposit-error'] ?? null);
        $user = new User($conn, $user_id);
        ?>
        <div class="balance">
            <p>ยอดเงินคงเหลือ: ฿<?php echo number_format($user->getWalletBalance(), 2); ?></p> <!-- Display current balance -->
        </div>
        <input type="number" name="amount" placeholder="จำนวนที่ต้องการฝาก" required max="5000" min="0"><br>
        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="3" name="transaction_type_id"> <!--  Deposit in tb_transaction_type 3 -->
        <input type="submit" value="ฝาก">
    </form>
</div>
</body>
</html>