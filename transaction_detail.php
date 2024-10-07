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
$transac_id = $_GET['id'];
$sql = "SELECT * FROM tb_transaction t INNER JOIN tb_user u ON u.user_id = t.user_id INNER JOIN tb_transaction_type tt ON tt.transaction_type_id = t.transaction_type_id WHERE t.transaction_id = '$transac_id'";
$result = mysqli_query($conn, $sql);

$sql2 = "SELECT * FROM tb_transaction t INNER JOIN tb_user u ON u.user_id = t.recipient_user_id WHERE t.transaction_id = '$transac_id'";
$result2 = mysqli_query($conn, $sql2);

if (isset($_SESSION['user_id'])) {
    $islogin = true;
    $user_id = $_SESSION['user_id'];
    $user = new User($conn, $user_id);
    $userPoint = new Point($conn, $user_id);
    $transaction = new Transaction($conn, $user_id);
    $transactionType = new TransactionType($conn);
    $userPoint->deleteExpiredPoints();
    $points_to_expire = $userPoint->getPointsExpiringInOneDay();
    $expire_day = date('Y-m-d', strtotime('+1 day'));
    $points_expire_message = sprintf("%d Pts. สามารถใช้ได้ภายใน %s", $points_to_expire, $expire_day);
}

if ($_SESSION['permission'] == 1) {
    ?>

    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Transaction Detail</title>
        <link rel="stylesheet" href="css/dashboard.css">
        <link rel="stylesheet" href="css/profile.css">
        <link rel="stylesheet" href="css/navbar.css">
        <link rel="stylesheet" href="css/login.css">
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
                    if ($_SESSION['permission'] == 1) {
                        echo "<a href='for_admin.php'>Admin</a>";
                    }
                    echo "<a href='action/logout.php'>Logout</a>";
                    echo "</div></div>";
                }
                ?>

            </div>
            <div class="dashboard">
                <div class="main-content">
                    <div class="recent-activity">
                        <h2>ประวัติธุรกรรม</h2>
                        <?php if ($islogin): ?>
                            <table class="activity-table">
                                <thead>
                                    <tr>
                                        <th>transaction_id</th>
                                        <th>ชื่อผู้ใช้</th>
                                        <th>ประเภทธุรกรรม</th>
                                        <th>วันที่ทำการ</th>
                                        <th>ค่าธรรมเนียม</th>
                                        <th>จำนวนเงิน</th>
                                        <th>ผู้รับ/ผู้ส่ง</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($arr = mysqli_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <th><?php echo $arr['transaction_id'] ?></th>
                                            <th><a
                                                    href="user_detail.php?id=<?php echo $arr['user_id'] ?>"><?php echo $arr['username'] ?></a>
                                            </th>
                                            <th><?php echo $arr['transaction_type_name'] ?></th>
                                            <th><?php echo $arr['created_at'] ?></th>
                                            <th><?php echo number_format($arr['fee_amount']) ?></th>
                                            <th><?php echo number_format($arr['amount']) ?></th>
                                            <?php
                                            $recipient = mysqli_fetch_array($result2);
                                            if (!empty($recipient['recipient_user_id'])) {
                                                ?>

                                                <th>
                                                    <a
                                                        href="user_detail.php?id=<?php echo $recipient['recipient_user_id'] ?>"><?php echo $recipient['username'] ?></a>
                                                </th>
                                            <?php } else { ?>
                                                <th>-</th>
                                            <?php } ?>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <br>
                            <h2>เข้าสู่ระบบ เพื่อดูข้อมูลธุรกรรม</h2>
                            <br>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script src=script/LoadContent.js></script>
    </body>

    </html>
    <?php
} else
    header("location:index.php");
?>