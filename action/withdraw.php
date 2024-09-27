<?php

use common\Wallet;

include("../connect.inc.php");
include("../common/Wallet.php");

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount'])) {
    $wallet = new Wallet($conn, $_SESSION['user_id']);
    $result = $wallet->withdraw($_POST['amount']);
    $fallback = $wallet->getFallback();

    if ($wallet->getFlag()) {
        header("Location: ../form/withdraw.php?withdraw-handle=" . $fallback);
        exit();
    } else {
        header("Location: ../form/withdraw.php?withdraw-error=". $fallback);
        exit();
    }
} else {
    header("Location: ../form/withdraw.php?withdraw-error=Invalid Input.");
    exit();
}
