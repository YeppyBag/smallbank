<?php

use common\Wallet;

include("../connect.inc.php");
include("../common/Wallet.php");

if ((!empty($_SESSION['user_id']) && isset($_POST['user_id'])) && isset($_POST['amount'])) {
    $wallet = new Wallet($conn, $_SESSION['user_id']);
    header("Location: ../form/deposit.php?deposit-handle=Successfully Deposit amount");
    $result = $wallet->deposit($_POST['amount']);

    echo $result;
} else {
    header("Location: ../form/deposit.php?deposit-error=Fail to Deposit.");
}
