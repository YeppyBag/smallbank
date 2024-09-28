<?php

use common\Fee;
use common\User;

include "../connect.inc.php";
require_once "../common/User.php";
require_once "../common/Fee.php";

if (!empty($_SESSION['user_id']) && isset($_POST['user_id']) && isset($_POST['amount'])) {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $receivername = $_POST['receiver'];
    $transferType = $_POST['transfer_type'];
    $transaction_type_id = $_POST['transaction_type_id'];
    $sender = new User($conn,$user_id);
    if ($amount <= 0) {
        header("Location: ../form/transfer.php?transfer-error=Invalid amount.");
        exit();
    }

    // Check if receiver exists based on the transfer type
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
        $receiver_username = $row['username'];
        $fee = new Fee($conn);
        $amountfee = $amount * $fee->getSenderFee();

        // Display the confirmation form
        ?>
        <!doctype html>
        <html lang="th">
        <head>
            <link rel="stylesheet" href="../css/confirm.css">
            <link rel="stylesheet" href="../css/profile.css">
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirm Transfer</title>
        </head>
        <body>
        <div class="container">
            <h2>Confirm Transfer</h2>
            <div class="confirmation-details">
                <div class="profile">
                    <div class="profile-image" style="background-image: url('<?php echo "../" . $sender->getProfile(); ?>');"></div>
                    <div class="arrow-icon">→</div>
                    <div class="profile-image" style="background-image: url('<?php echo "../" . $receiver->getProfile(); ?>');"></div>
                </div>

                <p><strong>จาก:</strong> <?= htmlspecialchars($sender->getUsername()) ?></p>
                <p><strong>ไปยัง:</strong> <?= htmlspecialchars($receiver_username) ?></p>
                <p><strong>จำนวนเงิน:</strong> <?= htmlspecialchars($amount) ?></p>
                <p><strong>ค่าธรรมเนียม:</strong> <?= htmlspecialchars($amountfee) ?></p> <!-- Adjust this as necessary -->
            </div>
            <form method="POST" action="../action/money_transaction.php">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($sender->getId())?>">
                <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
                <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiver_id) ?>">
                <input type="hidden" name="transaction_type_id" value="<?= htmlspecialchars($transaction_type_id) ?>" > <!-- Send in tb_transaction_type 2 -->
                <input type="submit" value="ยืนยัน" class="btn-confirm">
                <a href="../form/transfer.php" class="btn-cancel">ยกเลิก</a>
            </form>
        </div>
        </body>
        </html>
        <?php
        exit();
    } else {
        header("Location: ../form/transfer.php?transfer-error=Receiver not found.");
        exit();
    }
} else {
    header("Location: ../form/transfer.php?transfer-error=Invalid Input.");
    exit();
}
