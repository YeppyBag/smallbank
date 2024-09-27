<?php

use common\Transaction;
use common\User;

include("../connect.inc.php");
require_once "../common/User.php";
require_once "../common/Transaction.php";

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount'])) {
    if ($_POST['amount'] > 5000) {
        // Redirect with an error message
        header("Location: ../your_deposit_page.php?deposit-error=Deposit amount cannot exceed 5000");
        exit();
    }
    try {
//        $user = new User($conn, $_POST['user_id']);
        $user_id = $_POST['user_id'];
        $transaction = new Transaction($conn);

        $transactionType = $_POST['transaction_type_id'];
        $amount = $_POST['amount'];
        $fallback = "";

        if ($transactionType == 3) {
            $result = $transaction->deposit($user_id, $amount);
            $fallback = $result;
        } elseif ($transactionType == 4) {
            $result = $transaction->withdraw($user_id,$amount);
            $fallback = $result;
        } else {
            throw new Exception("Invalid transaction type.");
        }

        if (strpos($fallback, 'เสร็จ') !== false) {
            if ($transactionType == 3) { // Deposit
                header("Location: ../form/deposit.php?deposit-handle=" . $fallback);
            } elseif ($transactionType == 4) { // Withdraw
                header("Location: ../form/withdraw.php?withdraw-handle=" . $fallback);
            }
        } else {
            if ($transactionType == 3) { // Deposit
                header("Location: ../form/deposit.php?deposit-error=" . $fallback);
            } elseif ($transactionType == 4) { // Withdraw
                header("Location: ../form/withdraw.php?withdraw-error=" . $fallback);
            }
        }
    } catch (Exception $e) {
        header("Location: ../form/deposit.php?deposit-error=" . $e->getMessage());
    }
    exit();
} else {
    header("Location: ../form/deposit.php?deposit-error=" . "Invalid Input.");
    exit();
}
