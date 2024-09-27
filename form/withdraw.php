<?php
include "../connect.inc.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/redirect.css">
    <link rel="stylesheet" href="../css/deposit.css">
    <title>Withdraw</title>
</head>
<body>
<form name="withdraw_money" method="post" action="../action/withdraw.php">
    <?php if (isset($_GET['withdraw-handle'])): ?>
        <div class="handle-message">
            <?php echo htmlspecialchars($_GET['withdraw-handle']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['withdraw-error'])): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($_GET['withdraw-error']); ?>
        </div>
    <?php endif; ?>
    <input type="number" name="amount" placeholder="ใส่จำนวนเงิน" required><br>
    <input type="hidden" value="<?php echo $_SESSION['user_id'] ?>" name="user_id">
    <input type="submit" value="ถอน">
</form>
</body>
</html>