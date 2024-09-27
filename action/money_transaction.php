<?php

use common\Transaction;

include("../connect.inc.php");
require_once "../common/User.php";
require_once "../common/Transaction.php";

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $transactionType = $_POST['transaction_type_id'];

    if ($amount > 5000) {
        // Redirect with an error message
        header("Location: ../your_deposit_page.php?deposit-error=Deposit amount cannot exceed 5000");
        exit();
    }

    $transaction = new Transaction($conn, $user_id);

    if ($transactionType == 3) {
        $result = $transaction->deposit($amount);
    } elseif ($transactionType == 4) {
        $result = $transaction->withdraw($amount);
    } else {
        header("Location: ../form/deposit.php?deposit-error=Invalid transaction type.");
        exit();
    }

    if (strpos($result, 'สำเร็จ') !== false) {
        if ($transactionType == 3) { // Deposit
            header("Location: ../form/deposit.php?deposit-handle=" . $result);
        } elseif ($transactionType == 4) { // Withdraw
            header("Location: ../form/withdraw.php?withdraw-handle=" . $result);
        }
    } else {
        if ($transactionType == 3) {
            header("Location: ../form/deposit.php?deposit-error=" . $result);
        } elseif ($transactionType == 4) {
            header("Location: ../form/withdraw.php?withdraw-error=" . $result);
        }
    }
    exit();
} else {
    header("Location: ../form/deposit.php?deposit-error=Invalid Input.");
    exit();
}