<?php

use common\Fee;
use common\Point;
use common\User;

include "../connect.inc.php";
require_once "../common/User.php";
require_once "../common/Fee.php";
require_once "../common/Point.php";
require_once "../common/Config.php";

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount']) && isset($_POST['transaction_type_id'])) {
    $user_id = $_POST['user_id'];
    $amount = (float) $_POST['amount'];
    $transaction_type_id = (int) $_POST['transaction_type_id'];

    $usePoint = $_POST['point_used'] ?? 0;

    $user = new User($conn, $user_id);
    $fee = new Fee($conn);
    $point = new Point($conn, $user_id);

    $userPoint = $point->getPoints();

    $feeRate = $fee->getFeeRate($amount);
    $fee_amount = $fee->getFeeAmount($amount);

    $newfee_amount = $fee_amount;
    $points_to_use = 0;

    if ($usePoint == 1 && $userPoint >= Config::$pointRequirement && Config::$pointRequirement > 0) {
        $available_points = floor((int) $userPoint / Config::$pointRequirement) * Config::$pointRequirement;
        $points_to_use = min($fee_amount * Config::$pointRequirement, $available_points);
        $newfee_amount = $fee_amount - floor($points_to_use / Config::$pointRequirement);
    } else {
        $newfee_amount = $fee_amount;
    }

    if ($transaction_type_id == 3 && $amount > 5000) {
        header("Location: ../form/deposit_page.php?deposit-error=Deposit amount cannot exceed 5000");
        exit();
    }

    $subtotal = ($usePoint != 0) ? $amount - $newfee_amount : $amount - $fee_amount;

    include '../form/deposit_confirm.php';
    exit();
} else {
    header("Location: ../form/deposit.php?deposit-error=Invalid Input.");
    exit();
}
