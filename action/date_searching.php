<?php

include("../connect.inc.php");
include("../transactionTable.php");

$islogin = isset($_SESSION['user_id']);
if ($islogin) $user_id = $_SESSION['user_id'];

$begin = $_POST['begin'];
$to = $_POST['to'];

$sql = "SELECT 
    t.transaction_id, 
    u.username, 
    tt.transaction_type_name, 
    t.created_at, 
    t.fee_amount, 
    t.amount
FROM tb_transaction t
INNER JOIN tb_user u ON u.user_id = t.user_id
INNER JOIN tb_transaction_type tt ON tt.transaction_type_id = t.transaction_type_id
WHERE t.user_id = $user_id 
  AND DATE(t.created_at) BETWEEN '$begin' AND '$to'
ORDER BY t.created_at DESC;
";
$result = mysqli_query($conn, $sql);

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="navbar">
            <a href="../index.php">
                <h1 class="logo">SmallBank</h1>
            </a>
            <?php
            if (!$islogin) {
                echo "<a href='../form/login.php'>LOGIN/REGISTER</a>";
                echo '<div class="dropdown-login" id="login-form-container" style="display: none;"></div>';
            } else {
                echo "<div class='dropdown'>";
                echo "<a href='#'>Profile</a>";
                echo "<div class='dropdown-content'>";
                echo "<a href='../form/setting.php'>Setting</a>";
                if ($_SESSION['permission'] == 1) {
                    echo "<a href='../for_admin.php'>Admin</a>";
                }
                echo "<a href='../action/logout.php'>Logout</a>";
                echo "</div></div>";
            }
            ?>

        </div>
        <div class="dashboard">
            <div class="main-content">
                <div class="recent-activity">
                    <h2>ประวัติธุรกรรม</h2>
                    <?php if ($islogin): ?>
                        <?php renderTransactionTableDateQuery($result, false); ?>
                    <?php else: ?>
                        <br>
                        <h2>เข้าสู่ระบบ เพื่อดูข้อมูลธุรกรรม</h2>
                        <br>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>