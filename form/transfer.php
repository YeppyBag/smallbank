<?php

use common\FeatureUtil;

include "../common/FeatureUtil.php";
include "../connect.inc.php";
?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/deposit.css">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/login.js"></script>
    <title>Transfer</title>
</head>
<body>
<div class="container">
    <form name = "transfer_money" method="POST" action="../action/money_transaction.php">
        <?php
        FeatureUtil::displayMessage('handle', $_GET['transfer-handle'] ?? null);
        FeatureUtil::displayMessage('error', $_GET['transfer-error'] ?? null);
        ?>
        <input type="text" name="receiver" placeholder="Receiver Username"><br>

        <input type="number" name="amount" placeholder="Amount"><br>
        <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
        <input type="hidden" value="2" name="transaction_type_id"> <!--  ส่ง ใน tb_transaction_type 2 - send  -->
        <input type="submit" value="Send">
        <input type="reset" value="Cancel">
    </form>
</div>
</body>
</html>
