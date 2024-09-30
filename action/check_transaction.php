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
        $fee = new Fee($conn);
        $amountfee = $amount * $fee->getSenderFee();
        $subtotol = $amountfee + $amount;

        ?>
        <html lang="th">
        <head>
            <link rel="stylesheet" href="../css/confirm.css">
            <link rel="stylesheet" href="../css/profile.css">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
            </style>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirm Transfer</title>
        </head>
        <body>
        <div class="container">
            <div class="card cart">
                <label class="title">ตรวจสอบ</label>
                <div class="steps">
                    <div class="step">
                        <div class="profile">
                            <div class="profile-image" style="background-image: url('<?php echo "../" . $sender->getProfile(); ?>');"></div>
                            <div class="arrow-icon">→</div>
                            <div class="profile-image" style="background-image: url('<?php echo "../" . $receiver->getProfile(); ?>');"></div>
                        </div>
                        <hr />
                        <div class="transaction-info">
                            <span>TRANSACTION INFO</span>
                            <div class="transaction-details">
                                <p class="sender">ผู้โอน: <?php echo $sender->getUsername();?></p>
                                <p class="receiver">ผู้รับ: <?php echo $receivername; ?></p>
                            </div>
                        </div>
                        <hr />
                        <div class="payments">
                            <span>PAYMENT</span>
                            <div class="details">
                                <span>จำนวนเงินทั้งหมด :</span>
                                <span><?= number_format($amount,2) ?> บาท</span>
                                <span>ค่าธรรมเนียม (<?= $fee->getSenderFee() * 100?>%) : </span>
                                <span><?= number_format($amountfee,2) ?> บาท</span>
                                <span>แต้มที่จะได้รับ : </span>
                                <span><?= number_format(Point::promotionPointGain($amount)) ?> Pts.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card checkout">
                    <div class="footer">
                        <label class="price"><?= number_format($subtotol,2) ?> บาท</label>
                        <form method="POST" action="../action/money_transaction.php">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($sender->getId())?>">
                            <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiver_id) ?>">
                            <input type="hidden" name="transaction_type_id" value="<?= htmlspecialchars($transaction_type_id) ?>" > <!-- Send in tb_transaction_type 2 -->
                            <input type="submit" value="ยืนยัน" class="checkout-btn">
                        </form>
                    </div>
                </div>
            </div>
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
