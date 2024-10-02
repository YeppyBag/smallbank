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
    $receivername = $_POST['receiver'];
    $transferType = $_POST['transfer_type'];
    $transaction_type_id = $_POST['transaction_type_id'];
    $usePoint = $_POST['point_used'] ?? 0;

    $sender = new User($conn,$user_id);
    $fee = new Fee($conn);
    $point = new Point($conn, $user_id);
    $fee_amount = $fee->getSenderFee();

    if ($usePoint == 1) {
        $available_points = $point->getPoints();
        $points_to_use = min($fee_amount, $available_points);
        $newfee_amount = $fee_amount - $points_to_use; // Subtract points used from fee amount
    }

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
        $fee = new Fee($conn);
        $fee_amount = $amount * $fee->getSenderFee();

        if ($usePoint == 1) {
            $available_points = $point->getPoints();
            $points_to_use = min($fee_amount, $available_points);
            $newfee_amount = $fee_amount - $points_to_use;
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
