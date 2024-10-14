<?php

use common\Transaction;

include("../connect.inc.php");
include("../common/TransactionType.php");
require_once "../common/User.php";
require_once "../common/Transaction.php";

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $transactionType = $_POST['transaction_type_id'];

    $usePoint = $_POST['point_used'] ?? 0;
    $amountUsed = $_POST["amount_used"] ?? 0;

    if ($transactionType == TransactionType::Deposit && $amount > Config::$depositMaximum) {
        header("Location: ..form/deposit_page.php?deposit-error=Deposit amount cannot exceed 5000");
        exit();
    }

    $transaction = new Transaction($conn, $user_id);

    if ($transactionType == getTransactionTypeValue(TransactionType::Deposit)) {
        $result = $transaction->deposit($amount, $usePoint,$amountUsed);
    } elseif ($transactionType == getTransactionTypeValue(TransactionType::Withdraw)) {
        $result = $transaction->withdraw($amount);
    } elseif ($transactionType == getTransactionTypeValue(TransactionType::Send)) {
        $receiver_id = $_POST['receiver_id'];
        $result = $transaction->send($amount, $receiver_id, $usePoint, $amountUsed);
    } else {
        header("Location: ../form/deposit.php?deposit-error=Invalid transaction type.");
        exit();
    }

    if (str_contains($result, 'สำเร็จ')) {
        if ($transactionType == getTransactionTypeValue(TransactionType::Deposit)) { // Deposit
            header("Location: ../form/deposit.php?deposit-handle=" . urlencode($result));
        } elseif ($transactionType == getTransactionTypeValue(TransactionType::Withdraw)) { // Withdraw
            header("Location: ../form/withdraw.php?withdraw-handle=" . urlencode($result));
        } elseif ($transactionType == getTransactionTypeValue(TransactionType::Send)) { // Send
            header("Location: ../form/transfer.php?transfer-handle=" . urlencode($result));
        }
    } else {
        if ($transactionType == getTransactionTypeValue(TransactionType::Deposit)) {
            header("Location: ../form/deposit.php?deposit-error=" . urlencode($result));
        } elseif ($transactionType == getTransactionTypeValue(TransactionType::Withdraw)) {
            header("Location: ../form/withdraw.php?withdraw-error=" . urlencode($result));
        } elseif ($transactionType == getTransactionTypeValue(TransactionType::Send)) {
            header("Location: ../form/transfer.php?transfer-error=" . urlencode($result));
        }
    }
    exit();
} else {
    header("Location: ../form/index.php?transfer-error=Invalid Input.");
    exit();
}
