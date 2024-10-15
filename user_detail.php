<?php
use common\Transaction;

include("connect.inc.php");
include "common/Transaction.php";
include "common/User.php";
include "transactionTable.php";
$islogin = isset($_SESSION['user_id']);
if ($islogin) {
    $user_id = $_GET['id'];
    $transaction = new Transaction($conn, $user_id);
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
                    <?php renderTransactionTable($islogin,$user_id,$transaction );?>
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
