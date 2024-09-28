<?php
use common\FeatureUtil;

include "../connect.inc.php";
include "../common/FeatureUtil.php";
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/deposit.css">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>" />
    <script src="../script/login.js"></script>
    <script src="../script/confirmAction.js"></script>
    <title>Withdraw</title>
</head>
<body>
<div class="container">
    <h2>ถอนเงิน</h2>
    <form name="withdraw_money" method="post" action="../action/money_transaction.php" onsubmit="confirmAction(event, 'Withdraw')">
        <?php
        FeatureUtil::displayMessage('handle', $_GET['withdraw-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['withdraw-error'] ?? null);
        ?>
        <input type="number" name="amount" placeholder="Enter Amount" required>
        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="4" name="transaction_type_id"> <!--  Withdraw in tb_transaction_type 4 -->
        <input type="submit" value="ถอน">
    </form>
</div>
</body>
</html>
