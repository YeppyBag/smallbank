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
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>" />
    <script src="../script/Login.js"></script>
    <script src="../script/ConfirmAction.js"></script>
    <title>Withdraw</title>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="../index.php">Home</a>
    </div>
    <h2>ถอนเงิน</h2>
    <form name="withdraw_money" method="post" action="../action/money_transaction.php" onsubmit="confirmAction(event, 'Withdraw')">
        <?php
        $user_id = $_SESSION['user_id'];
        FeatureUtil::displayMessage('handle', $_GET['withdraw-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['withdraw-error'] ?? null);
        $user = new User($conn, $user_id);
        ?>
        <div class="balance">
            <p>ยอดเงินที่ทำการได้: ฿<?php echo number_format($user->getWalletBalance(), 2); ?></p> <!-- Display current balance -->
        </div>
        <input type="number" name="amount" placeholder="จำนวนที่ต้องการถอน" required>
        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="4" name="transaction_type_id"> <!--  Withdraw in tb_transaction_type 4 -->
        <input type="submit" value="ถอน">
    </form>
</div>
</body>
</html>
