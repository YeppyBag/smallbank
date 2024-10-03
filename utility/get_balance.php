<?php

use common\User;

include "../connect.inc.php";
require '../common/User.php';

if (isset($_SESSION['user_id'])) {
    $user = new User($conn,$_SESSION['user_id']);
    $balance = $user->getWalletBalance();
    echo json_encode(['balance' => $balance]);
} else {
    echo json_encode(['balance' => 0]);
}
