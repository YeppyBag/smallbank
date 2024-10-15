<?php

use common\Point;
use common\Transaction;
use common\User;

include("connect.inc.php");
include "common/Transaction.php";
include "common/User.php";
include "common/point.php";
include "transactionTable.php";
$islogin = false;
if (isset($_SESSION['user_id'])) {
    $islogin = true;
    $user_id = $_SESSION['user_id'];
    $user = new User($conn, $user_id);
    $userPoint = new Point($conn, $user_id);
    $transaction = new Transaction($conn, $user_id);
    $userPoint->deleteExpiredPoints();
    $points_to_expire = $userPoint->getPointsExpiringInOneDay();
    $expire_day = date('Y-m-d', strtotime('+'. Config::$pointExpireInOneDay .' day'));
    $points_expire_message = sprintf("%d Pts. สามารถใช้ได้ภายใน %s", $points_to_expire, $expire_day);
}
date_default_timezone_set('Asia/Bangkok');
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
            <a href="index.php">
                <h1 class="logo">SmallBank</h1>
            </a>
            <?php
            if (!$islogin) {
                echo "<a href='form/login.php'>LOGIN/REGISTER</a>";
                echo '<div class="dropdown-login" id="login-form-container" style="display: none;"></div>';
            } else {
                echo "<div class='dropdown'>";
                echo "<a href='#'>Profile</a>";
                echo "<div class='dropdown-content'>";
                echo "<a href='form/setting.php'>Setting</a>";
                if($_SESSION['permission'] == 1) echo "<a href='for_admin.php'>Admin</a>";
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
                    else
                        echo "img/default-profile.png";
                    ?>
                        ');"></div>
                </div>

                <div class="balance-section">
                    <div class="balance-current">
                        <h2>
                            <?php if ($islogin): ?>
                                ยินดีต้อนรับ <?php echo "<h1>" . $user->getUsername() . "</h1>"; ?> จำนวนเงินปัจจุบัน
                            <?php else: ?>
                                ยินดีต้อนรับ ท่านสมาชิก
                            <?php endif; ?>
                        </h2>
                    </div>
                    <div class="balance-amount">
                        <?php if ($islogin): ?>
                            <h1 id="wallet-balance"><?php echo $currency . number_format($user->getWalletBalance(), 2); ?>
                            </h1>
                            <p class="available-text">มีอยู่</p>
                        <?php else: ?>
                            <p class="available-text">กรุณาเข้าสู่ระบบพื่อดูยอดเงิน</p>
                        <?php endif; ?>
                    </div>

                    <div class="point-amount">
                        <?php if ($islogin): ?>
                            <a href="#" class="point-link" id="point-transaction-link"
                                data-url="form/point_transaction_info.php">
                                <p class="point"><?php echo number_format($userPoint->getPoints()); ?></p>
                            </a>
                            <p class="available-text">Pts.</p>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <?php if ($points_to_expire > 0): ?>
                            <p class="point-almost-expire"><?= $points_expire_message ?></p>
                        <?php else: ?>
                            <p class="point-almost-expire">ยังไม่มีแต้มจะหมดอายุเร็วๆนี้</p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="actions">
                        <?php if ($islogin): ?>
                            <button class="btn" data-url="form/deposit.php">ฝากเงิน</button>
                            <button class="btn" data-url="form/withdraw.php">ถอน</button>
                            <button class="btn" data-url="form/transfer.php">โอนเงิน</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="recent-activity">
                    <?php if($islogin){?>
                        <div class="search">
                        <h2>ประวัติธุรกรรม</h2>
                        <form action="action/date_searching.php" method="POST">
                            <input type="datetime-local" name="begin" required>-
                            <input type="datetime-local" name="to" required>
                            <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']?>">
                            <button type="submit">ค้นหา</button>
                        </form>
                    </div>
                    <?php }?>
                    <?php if ($islogin)renderTransactionTable($islogin,$user_id,$transaction);?>
                </div>
            </div>
        </div>
    </div>
    <script src=script/LoadContent.js></script>
</body>

</html>