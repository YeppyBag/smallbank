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
    $user_id = $_GET['id'];
    $user = new User($conn, $user_id);
    $userPoint = new Point($conn, $user_id);
    $transaction = new Transaction($conn, $user_id);
    $transactionType = new TransactionType($conn);
    $userPoint->deleteExpiredPoints();
    $points_to_expire = $userPoint->getPointsExpiringInOneDay();
    $expire_day = date('Y-m-d', strtotime('+1 day'));
    $points_expire_message = sprintf("%d Pts. สามารถใช้ได้ภายใน %s", $points_to_expire, $expire_day);
}
date_default_timezone_set('Asia/Bangkok');
$currency = '฿';
if($_SESSION['permission'] == 1){
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>User Transaction Detail</title>
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
                if($_SESSION['permission'] == 1){
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
                                    <th>ชื่อธุรกรรม</th>
                                    <th>ประเภทธุรกรรม</th>
                                    <th>วันที่ทำการ</th>
                                    <th>ค่าธรรมเนียม</th>
                                    <th>จำนวนเงิน</th>
                                    <th>ยอดสุทธิ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $transactionData = $transaction->getTransactionByUserIdJoinTable($user_id);
                                $map = [
                                    "1" => "รับเงิน",
                                    "2" => "โอนเงิน",
                                    "3" => "ฝากเงิน",
                                    "4" => "ถอนเงิน"
                                ];
                                foreach ($transactionData as $transaction):
                                    $senderUserId = $transaction['user_id'];
                                    $receiverUsername = $transaction['recipient_username'];
                                    $transactionDate = date('d/m/Y H:i:s', strtotime($transaction['created_at']));
                                    $prefix = 'SmallBank';

                                    if (!empty($senderUserId)) {
                                        $prefix = $transaction['transaction_type_id'] == 1 ? 'โอนจาก ' . $receiverUsername :
                                            ($transaction['transaction_type_id'] == 2 ? 'โอนเงินไปยัง ' . $receiverUsername : $prefix);
                                    }
                                    $transactionType = $map[$transaction['transaction_type_id']] ?? 'Unknown';
                                    ?>
                                    <tr>
                                        <th><?php echo htmlspecialchars($prefix); ?></th>
                                        <th><?php echo htmlspecialchars($transactionType); ?></th>
                                        <th><?php echo $transactionDate; ?></th>
                                        <th>฿<?php echo number_format(($transaction['fee_amount']), 2); ?></th>
                                        <th class="amount">฿<?php echo number_format(($transaction['amount']), 2); ?></th>
                                        <th class="amount">
                                            ฿<?php echo number_format(($transaction['amount']) + ($transaction['fee_amount']), 2); ?>
                                        </th>
                                    </tr>
                                <?php endforeach; ?>
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
}else {
    header("location:index.php");
}

?>
