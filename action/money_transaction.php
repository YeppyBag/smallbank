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
        header("Location: ../your_deposit_page.php?deposit-error=Deposit amount cannot exceed 5000");
        exit();
    }

    $transaction = new Transaction($conn, $user_id);

    if ($transactionType == 3) { // Deposit
        $result = $transaction->deposit($amount, $transactionType);
    } elseif ($transactionType == 4) { // Withdraw
        $result = $transaction->withdraw($amount, $transactionType);
    } elseif ($transactionType == 2) { // Send
        $receiver_id = $_POST['receiver_id'];
        $result = $transaction->send($amount, $transactionType, $receiver_id);
    } else {
        header("Location: ../form/deposit.php?deposit-error=Invalid transaction type.");
        exit();
    }

    if (strpos($result, 'สำเร็จ') !== false) {
        if ($transactionType == 3) { // Deposit
            header("Location: ../form/deposit.php?deposit-handle=" . urlencode($result));
        } elseif ($transactionType == 4) { // Withdraw
            header("Location: ../form/withdraw.php?withdraw-handle=" . urlencode($result));
        } elseif ($transactionType == 2) { // Send
            header("Location: ../form/transfer.php?transfer-handle=" . urlencode($result));
        }
    } else {
        if ($transactionType == 3) {
            header("Location: ../form/deposit.php?deposit-error=" . urlencode($result));
        } elseif ($transactionType == 4) {
            header("Location: ../form/withdraw.php?withdraw-error=" . urlencode($result));
        } elseif ($transactionType == 2) {
            header("Location: ../form/transfer.php?transfer-error=" . urlencode($result));
        }
    }
    exit();
} else {
    header("Location: ../form/index.php?transfer-error=Invalid Input.");
    exit();
}
