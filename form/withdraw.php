<?php

use common\FeatureUtil;

include "../connect.inc.php";
include "../common/FeatureUtil.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/deposit.css">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>" />
    <script src="../script/login.js"></script>
    <title>Withdraw</title>
</head>
<body>
<form name="withdraw_money" method="post" action="../action/money_transaction.php">
    <?php
    FeatureUtil::displayMessage('handle', $_GET['withdraw-handle'] ?? null);
    FeatureUtil::displayMessage('error', $_GET['withdraw-error'] ?? null);
    ?>
    <input type="number" name="amount" placeholder="ใส่จำนวนเงิน" required><br>
    <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
    <input type="hidden" value="4" name="transaction_type_id"> <!--  ถอนเงิน ใน tb_transaction_type 4 - withdraw  -->
    <input type="submit" value="ถอน">
</form>
</body>
</html>