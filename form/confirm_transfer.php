<?php
include "../connect.inc.php";
$receiver = $_POST['receiver'];
$sender = $_POST['user_id'];
$amount = $_POST['amount'];

$sql = "SELECT * FROM tb_user WHERE user_id = '$sender'";
$result = mysqli_query($conn, $sql);
$data_sender = mysqli_fetch_array($result);

$sql2 = "SELECT * FROM tb_user WHERE username = '$receiver'";
$result2 = mysqli_query($conn, $sql2);
$row = mysqli_num_rows($result2);
if (empty($row)) {
    echo "ไม่พบบัญชี";
} else {
    $data_receiver = mysqli_fetch_array($result2);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/con_transfer.css">
    <title>Confirm Transfer</title>
</head>

<body>
    <div class="container">
        <div class="context">
            <h2>ยืนยันการโอนเงิน</h2>
        </div>
        <div class="sender">
            <h4>จาก</h4>
            <?php echo $data_sender['username']; ?>
        </div>
        <div class="receiver">
            <h4>ผู้รับ</h4>
            <?php echo $data_receiver['username']?>
        </div>
        <div class="amount">
            <h4>จำนวนเงิน</h4>
            <?php echo $amount;?>
        </div>
        <div class="confirmbtn">
            <form action="../action/money_transaction.php" method="POST">
                <input type="hidden" name="sender" value="<?php echo $sender?>">
                <input type="hidden" name="receiver" value="<?php echo $receiver?>">
                <input type="hidden" name="amount" value="<?php echo $amount?>">
                <input class="btn"type="submit" value="ยืนยัน">
            </form>
        </div>
    </div>
</body>

</html>