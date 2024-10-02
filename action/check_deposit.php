<?php

use common\Fee;
use common\Point;
use common\User;

include "../connect.inc.php";
require_once "../common/User.php";
require_once "../common/Fee.php";
require_once "../common/Point.php";

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $transaction_type_id = $_POST['transaction_type_id'];

    $usePoint = $_POST['point_used'] ?? 0;

    $user = new User($conn, $user_id);
    $fee = new Fee($conn);
    $point = new Point($conn, $user_id);

    $feeRate = $fee->getFeeRate($amount);
    $fee_amount = $fee->getFeeAmount($amount);

    if ($usePoint == 1) {
        $available_points = $point->getPoints();
        $points_to_use = min($fee_amount, $available_points);
        $newfee_amount = $fee_amount - $points_to_use; // Subtract points used from fee amount
    }

    if ($transaction_type_id == 3 && $amount > 5000) {
        header("Location: ../form/deposit_page.php?deposit-error=Deposit amount cannot exceed 5000");
        exit();
    }

    $subtotol = ($usePoint != 0) ? $amount - $newfee_amount : $amount;

    include '../form/deposit_confirm.php';
    exit();
} else {
    header("Location: ../form/deposit.php?deposit-error=Invalid Input.");
    exit();
}
