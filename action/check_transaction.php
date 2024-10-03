<?php

use common\Fee;
use common\Point;
use common\User;

include "../connect.inc.php";
require_once "../common/User.php";
require_once "../common/Fee.php";
require_once "../common/Point.php";
require_once "../common/Config.php";

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $receivername = $_POST['receiver'];
    $transferType = $_POST['transfer_type'];
    $transaction_type_id = $_POST['transaction_type_id'];
    $usePoint = $_POST['point_used'] ?? 0;

    $sender = new User($conn,$user_id);
    $point = new Point($conn, $user_id);
    $userPoint = $point->getPoints();
    $fee = new Fee($conn);
    $fee_amount = $fee->getSenderFee();


    if ($amount <= 0) {
        header("Location: ../form/transfer.php?transfer-error=Invalid amount.");
        exit();
    }

    if ($transferType == 'username') {
        $query = "SELECT * FROM tb_user WHERE username = '$receivername'";
    } elseif ($transferType == 'email') {
        $query = "SELECT * FROM tb_user WHERE email = '$receivername'";
    } else {
        header("Location: ../form/transfer.php?transfer-error=Invalid transfer type.");
        exit();
    }

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $receiver_id = $row['user_id'];

        $receiver = new User($conn,$receiver_id);

        $feeRate = $fee->getFeeRate($amount);
        $fee_amount = $amount * $fee->getSenderFee();

        $newfee_amount = $fee_amount;
        $points_to_use = 0;

        if ($usePoint == 1 && $userPoint >= Config::$pointRequirement && Config::$pointRequirement > 0) {
            $available_points = floor((int) $userPoint / Config::$pointRequirement) * Config::$pointRequirement;
            $points_to_use = min($fee_amount * Config::$pointRequirement, $available_points);
            $newfee_amount = $fee_amount - floor($points_to_use / Config::$pointRequirement);
        } else {
            $newfee_amount = $fee_amount;
        }

        $subtotol = ($usePoint != 0) ?  $newfee_amount + $amount : $fee_amount + $amount;

        include '../form/transfer_confirm.php';
        exit();
    } else {
        header("Location: ../form/transfer.php?transfer-error=Receiver not found.");
        exit();
    }
} else {
    header("Location: ../form/transfer.php?transfer-error=Invalid Input.");
    exit();
}
