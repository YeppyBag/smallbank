<?php

use common\Point;
use common\Transaction;
use common\TransactionType;
use common\User;

include("connect.inc.php");
require_once "common/Transaction.php";
require_once "common/TransactionType.php";
require_once "common/User.php";
require_once "common/point.php";
$islogin = false;
if (isset($_SESSION['user_id'])) {
    $islogin = true;
    $user_id = $_SESSION['user_id'];
    $user = new User($conn, $user_id);
    $userPoint = new Point($conn, $user_id);
    $transaction = new Transaction($conn, $user_id);
    $transactionType = new TransactionType($conn);
    $userPoint->deleteExpiredPoints();
}
$currency = '฿';
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>SmallBank Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="navbar">
        <h1 class="logo">SmallBank</h1>
        <?php
        if (!$islogin) {
            echo "<a href='form/login.php'>SIGN UP / LOG IN</a>";
            echo '<div class="dropdown-login" id="login-form-container" style="display: none;"></div>';
        } else {
            echo "<div class='dropdown'>";
            echo "<a href='#'>Profile</a>";
            echo "<div class='dropdown-content'>";
            echo "<a href='form/setting.php'>Setting</a>";
            echo "<a href='action/logout.php'>Logout</a>";
            echo "</div></div>";
        }
        ?>
    </div>
    <div class="dashboard">
        <div class="sidebar">
            <div class="profile">
                <div class="profile-image" style="background-image: url('<?php if ($islogin)
                    echo $user->getProfile();
                else echo "img/default-profile.png";
                ?>
                        ');"></div>
            </div>

            <div class="balance-section">
                <div class="balance-current">
                    <h2>
                    <?php if ($islogin) : ?>
                    ยินดีต้อนรับ <?php $user->getUsername() ?>จำนวนเงินปัจจุบัน
                    <?php else : ?>
                        ยินดีต้อนรับ ท่านสมาชิก
                    <?php endif; ?>
                    </h2>
                </div>
                <div class="balance-amount">
                    <?php if ($islogin) echo "<h1>" . $currency . number_format($user->getWalletBalance(),2) . "</h1>"; ?>
                    <?php if ($islogin) : ?>
                    <p class="available-text">มีอยู่</p>
                    <?php else : ?>
                    <p class="available-text">กรุณาเข้าสู่ระบบพื่อดูยอดเงิน</p>
                    <?php endif; ?>
                </div>

                <div class="point-amount">
                    <?php if ($islogin) : ?>
                    <p class="point"><?php echo number_format($userPoint->getPoints()) ?> </p>
                        <p class="available-text">Pts.</p>
                    <?php endif;?>
                </div>
                <p class="point-almost-expire">1 Pts. can use till 1/10/2024</p>

                <div class="actions">
                    <?php if ($islogin) : ?>
                    <button class="btn" data-url="form/deposit.php">ฝากเงิน</button>
                    <button class="btn" data-url="form/withdraw.php">ถอน</button>
                    <button class="btn" data-url="form/transfer.php">โอนเงิน</button>
                    <?php endif;?>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="recent-activity">
                <h2>ประวัติธุรกรรม</h2>
                <?php if ($islogin) : ?>
                <table class="activity-table">
                    <thead>
                    <tr>
                        <th>ชื่อธุรกรรม</th>
                        <th>ประเภทธุรกรรม</th>
                        <th>วันที่ทำการ</th>
                        <th>แต้มที่ได้รับ</th>
                        <th>วันที่แต้มหมดอายุ</th>
                        <th>ค่าธรรมเนียม</th>
                        <th class="amount-th">จำนวนเงิน</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $transactionData = $transaction->getTransactionByUserIdOrderBy($user_id, "created_at DESC");
                    $pointTransaction = $userPoint->getTransactionHistory();
                    $pointHistory = $userPoint->getPointHistory();

                    $map = [
                        "1" => "รับเงิน",
                        "2" => "โอนเงิน",
                        "3" => "ฝากเงิน",
                        "4" => "ถอนเงิน"
                    ]; // Transaction type mapping

                    foreach ($transactionData as $transaction):
                        $senderUserId = $transaction['recipient_user_id'];
                        $transactionDate = date('d/m/Y H:i:s', strtotime($transaction['created_at']));

                        $prefix = 'SmallBank';

                        if (!empty($senderUserId)) {
                            $prefix = $transaction['transaction_type_id'] == 1 ? 'โอนจาก ' . $user->getUsername() :
                                ($transaction['transaction_type_id'] == 2 ? 'โอนเงินไปยัง ' . $user->getUsername() : $prefix);
                        }

                        $transactionType = isset($map[$transaction['transaction_type_id']]) ? $map[$transaction['transaction_type_id']] : 'Unknown';

                        $pointAmount = 0;
                        $pointExpirationDate = '-';

                        foreach ($pointHistory as $pointHistoryZ) {
                            if (date('Y-m-d H:i:s', strtotime($pointHistoryZ['created_at'])) == date('Y-m-d H:i:s', strtotime($transaction['created_at']))) {
                                $pointAmount = $pointHistoryZ['points'];
                                $pointExpirationDate = date('d/m/Y H:i:s', strtotime($pointHistoryZ['expiration_date']));
                                break;
                            }
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($prefix); ?></td>
                            <td><?php echo htmlspecialchars($transactionType); ?></td>
                            <td><?php echo $transactionDate; ?></td>
                            <td><?php echo number_format($pointAmount); ?></td>
                            <td><?php echo htmlspecialchars($pointExpirationDate); ?></td>
                            <td>฿<?php echo number_format(($transaction['fee_amount']), 2); ?></td>
                            <td class="amount">฿<?php echo number_format(($transaction['amount']), 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
                <?php else : ?>
                <br>
                <h2>เข้าสู่ระบบ เพื่อดูข้อมูลธุรกรรม</h2>
                <br>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="footer">
    <p>&copy; 2024 SmallBank,Peggy Bag. All rights reserved Version 0.9.0.</p>
</div>
<script>
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function() {
            this.innerHTML = 'รอซักครู่';
            this.disabled = true;
            window.location.href = this.getAttribute('data-url');
        });
    });
</script>
</body>
</html>
