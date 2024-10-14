<?php

use common\Point;
use common\Transaction;
use common\User;

include("connect.inc.php");
require_once "common/Transaction.php";
require_once "common/User.php";
require_once "common/point.php";
$islogin = false;
if (isset($_SESSION['user_id'])) {
    $islogin = true;
    $user_id = $_SESSION['user_id'];
    $user = new User($conn, $user_id);
    $userPoint = new Point($conn, $user_id);
    $transaction = new Transaction($conn, $user_id);
    $userPoint->deleteExpiredPoints();
    $points_to_expire = $userPoint->getPointsExpiringInOneDay();
    $expire_day = date('Y-m-d', strtotime('+1 day'));
}
$sql = "SELECT * FROM tb_transaction t INNER JOIN tb_user u ON u.user_id = t.user_id INNER JOIN tb_transaction_type tt ON tt.transaction_type_id = t.transaction_type_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
if ($_SESSION['permission'] == 1) {
    ?>

    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>SmallBank Admin</title>
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
                        <div class="search">
                            <h2>ประวัติธุรกรรม</h2>
                            <form action="action/admin_date_searching.php" method="POST">
                                <input type="datetime-local" name="begin" required>-
                                <input type="datetime-local" name="to" required>
                                <input type="hidden" name="id" value="<?php echo $_SESSION['user_id'] ?>">
                                <button type="submit">ค้นหา</button>
                            </form>
                        </div>
                        <?php if ($islogin): ?>
                            <table class="activity-table">
                                <thead>
                                    <tr>
                                        <th>รหัสธุรกรรม</th>
                                        <th>ชื่อผู้ใช้</th>
                                        <th>ประเภทธุรกรรม</th>
                                        <th>วันที่ทำการ</th>
                                        <th>ค่าธรรมเนียม</th>
                                        <th>จำนวนเงิน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($arr = mysqli_fetch_array($result)) { ?>
                                        <tr>
                                            <th><a href="transaction_detail.php?id=<?php echo $arr['transaction_id'] ?>"><?php echo $arr['transaction_id'] ?></a></th>
                                            <th><a href="user_detail.php?id=<?php echo $arr['user_id'] ?>"><?php echo $arr['username'] ?></a></th>
                                            <th><?php echo $arr['transaction_type_name'] ?></th>
                                            <th><?php echo $arr['created_at'] ?></th>
                                            <th><?php echo number_format($arr['fee_amount']) ?></th>
                                            <th><?php echo number_format($arr['amount']) ?></th>
                                        </tr>
                                    <?php } ?>
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