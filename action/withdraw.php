<?php

use common\Wallet;

include("../connect.inc.php");
include("../common/Wallet.php");

if ((!empty($_SESSION['user_id']) && isset($_POST['user_id'])) && isset($_POST['amount'])) {
    $wallet = new Wallet($conn, $_SESSION['user_id']);
    header("Location: ../form/withdraw.php?withdraw-handle=Successfully Withdraw");
    $result = $wallet->withdraw($_POST['amount']);
    echo $result;
} else {
    header("Location: ../form/withdraw.php?withdraw-error=Fail to Withdraw.");
}
