<?php
include "../connect.inc.php";
$amount = $_POST['amount'];
$user_id = $_POST['user_id'];
$type = $_POST['transaction_type_id'];

$sql = "SELECT * FROM tb_user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$data_sender = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/con_transfer.css">
    <title>Deposit Confirm</title>
</head>

<body>
    <div class="container">
        <div class="context">
            <h2>ยืนยันการฝากเงิน</h2>
        </div>
        <div class="sender">
            <h4>จาก</h4>
            <?php echo $data_sender['username']; ?>
        </div>
        <div class="amount">
            <h4>จำนวนเงิน</h4>
            <?php echo $amount; ?>
        </div>
        <div class="confirmbtn">
            <form action="../action/money_transaction.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $sender ?>">
                <input type="hidden" name="transaction_type" value="<?php echo $type ?>">
                <input type="hidden" name="amount" value="<?php echo $amount ?>">
                <input class="btn" type="submit" value="ยืนยัน">
            </form>
        </div>
    </div>
</body>

</html>