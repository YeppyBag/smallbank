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
    <script src="../script/ConfirmAction.js"></script>
    <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'false' : 'true'; ?>"/>
    <script src="../script/Login.js"></script>
    <title>Confirm Deposit</title>
</head>
<body>
<div class="container">
    <div class="card cart">
        <label class="title">ตรวจสอบ</label>
        <div class="steps">
            <div class="step">
                <div class="profile">
                    <div class="profile-image" style="background-image: url('<?php echo "../" . $user->getProfile(); ?>');"></div>
                </div>
                <hr />
                <div class="transaction-info">
                    <span>ธุรกรรมฝากเงิน</span>
                    <div class="transaction-details">
                        <p class="sender">ผู้ฝากเงิน: <?php echo $user->getUsername();?></p>
                    </div>
                </div>
                <hr />
                <div class="payments">
                    <span>รายละเอียด</span>
                    <div class="details">
                        <span>จำนวนเงินที่ฝาก :</span>
                        <span><?= number_format($amount, 2) ?> บาท</span>
                        <span>ค่าธรรมเนียม (<?= $feeRate ?>%) :</span>
                        <span>-<?= number_format($fee_amount, 2) ?> บาท</span>

                        <?php if ($usePoint == 1 && isset($points_to_use) && $points_to_use > 0): ?>
                            <span>ใช้แต้ม point <?= $points_to_use ?> หักลบค่าธรรมเนียม :</span>
                            <span>+<?= number_format($points_to_use, 2) ?> บาท</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card checkout">
            <div class="footer">
                <label class="price"><?= number_format($subtotol, 2) ?> บาท</label>
                <form method="POST" action="../action/money_transaction.php" onsubmit="confirmAction(event,'ฝากเงิน')">
                    <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id)?>">
                    <input type="hidden" name="transaction_type_id" value="<?= htmlspecialchars($transaction_type_id) ?>">
                    <input type="hidden" name="point_used" value="<?= htmlspecialchars($usePoint) ?>">
                    <input type="submit" value="ยืนยัน" class="checkout-btn">
                </form>
            </div>
        </div>
    </div>
    <form method="POST" action="../form/deposit.php">
        <button type="submit" class="cancel-btn">ยกเลิก</button>
    </form>

</div>
</body>
</html>
